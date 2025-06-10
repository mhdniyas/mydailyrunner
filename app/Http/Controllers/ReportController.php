<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\StockIn;
use App\Models\DailyStockCheck;
use App\Models\FinancialEntry;
use App\Models\FinancialCategory;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    /**
     * Display the stock report.
     */
    public function stock(Request $request)
    {
        $shopId = session('current_shop_id');
        
        // Get all products with their current stock
        $query = Product::where('shop_id', $shopId);
        
        // Filter by product if provided
        if ($request->has('product_id')) {
            $query->where('id', $request->product_id);
        }
        
        // Filter by stock level if provided
        if ($request->has('stock_level') && in_array($request->stock_level, ['low', 'out', 'normal'])) {
            if ($request->stock_level === 'low') {
                $query->where('current_stock', '>', 0)
                    ->where('current_stock', '<=', DB::raw('min_stock_level'));
            } elseif ($request->stock_level === 'out') {
                $query->where('current_stock', '<=', 0);
            } elseif ($request->stock_level === 'normal') {
                $query->where('current_stock', '>', DB::raw('min_stock_level'));
            }
        }
        
        $products = $query->orderBy('name')->get();
        
        // Calculate total stock value
        $totalStockValue = $products->sum(function ($product) {
            return $product->current_stock * $product->avg_cost;
        });
        
        // Get all products for filter
        $allProducts = Product::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        return view('reports.stock', [
            'products' => $products,
            'allProducts' => $allProducts,
            'selectedProduct' => $request->product_id ?? null,
            'stockLevel' => $request->stock_level ?? 'all',
            'totalStockValue' => $totalStockValue,
            'title' => 'Stock Report',
            'subtitle' => 'Current inventory status'
        ]);
    }

    /**
     * Display the discrepancy report.
     */
    public function discrepancy(Request $request)
    {
        $shopId = session('current_shop_id');
        
        // Get date range
        $fromDate = $request->from_date ?? now()->subDays(30)->format('Y-m-d');
        $toDate = $request->to_date ?? now()->format('Y-m-d');
        
        // Get all stock checks with discrepancies
        $query = DailyStockCheck::where('shop_id', $shopId)
            ->where('discrepancy', '!=', 0)
            ->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
            
        // Filter by product if provided
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        $discrepancies = $query->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        // Calculate summary stats
        $totalPositiveDiscrepancy = DailyStockCheck::where('shop_id', $shopId)
            ->where('discrepancy', '>', 0)
            ->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->sum('discrepancy');
            
        $totalNegativeDiscrepancy = DailyStockCheck::where('shop_id', $shopId)
            ->where('discrepancy', '<', 0)
            ->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59'])
            ->sum('discrepancy');
            
        // Get all products for filter
        $products = Product::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        return view('reports.discrepancy', [
            'discrepancies' => $discrepancies,
            'products' => $products,
            'selectedProduct' => $request->product_id ?? null,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalPositiveDiscrepancy' => $totalPositiveDiscrepancy,
            'totalNegativeDiscrepancy' => $totalNegativeDiscrepancy,
            'title' => 'Discrepancy Report',
            'subtitle' => 'Stock count differences'
        ]);
    }

    /**
     * Display the financial report.
     */
    public function financial(Request $request)
    {
        $shopId = session('current_shop_id');
        
        // Get date range
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->format('Y-m-d');
        
        // Get selected type (all, income, or expense)
        $selectedType = $request->type ?? 'all';
        
        // Get financial entries
        $query = FinancialEntry::where('shop_id', $shopId)
            ->whereBetween('entry_date', [$fromDate, $toDate]);
            
        // Filter by type if not 'all'
        if ($selectedType !== 'all') {
            $query->where('type', $selectedType);
        }
        
        // Filter by category if provided
        $selectedCategory = $request->category_id ?? null;
        if ($selectedCategory) {
            $query->where('financial_category_id', $selectedCategory);
        }
        
        $entries = $query->with('category')
            ->orderBy('entry_date', 'desc')
            ->paginate(15);
            
        // Get sales income
        $sales = Sale::where('shop_id', $shopId)
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->with('customer')
            ->get();
            
        // Calculate summary stats
        $totalSalesIncome = $sales->sum('paid_amount');
        $totalOtherIncome = FinancialEntry::where('shop_id', $shopId)
            ->where('type', 'income')
            ->whereBetween('entry_date', [$fromDate, $toDate])
            ->sum('amount');
        $totalIncome = $totalSalesIncome + $totalOtherIncome;
        
        $totalExpenses = FinancialEntry::where('shop_id', $shopId)
            ->where('type', 'expense')
            ->whereBetween('entry_date', [$fromDate, $toDate])
            ->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;
        
        // Group expenses by category
        $expensesByCategory = FinancialEntry::where('shop_id', $shopId)
            ->where('type', 'expense')
            ->whereBetween('entry_date', [$fromDate, $toDate])
            ->with('category')
            ->get()
            ->groupBy('financial_category_id')
            ->map(function ($items) {
                return [
                    'category' => $items->first()->category->name,
                    'amount' => $items->sum('amount')
                ];
            })
            ->sortByDesc('amount')
            ->values();
            
        // Get categories for filter
        $categories = FinancialCategory::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        // Prepare chart data
        $chartLabels = [];
        $incomeData = [];
        $expenseData = [];
        
        // Generate chart data by day for the selected period
        $startDate = \Carbon\Carbon::parse($fromDate);
        $endDate = \Carbon\Carbon::parse($toDate);
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            $dateStr = $currentDate->format('Y-m-d');
            $chartLabels[] = $currentDate->format('M d');
            
            // Get income for this day
            $dayIncome = FinancialEntry::where('shop_id', $shopId)
                ->where('type', 'income')
                ->whereDate('entry_date', $dateStr)
                ->sum('amount');
                
            // Add sales income for this day
            $daySalesIncome = Sale::where('shop_id', $shopId)
                ->whereDate('sale_date', $dateStr)
                ->sum('paid_amount');
                
            $incomeData[] = $dayIncome + $daySalesIncome;
            
            // Get expenses for this day
            $dayExpense = FinancialEntry::where('shop_id', $shopId)
                ->where('type', 'expense')
                ->whereDate('entry_date', $dateStr)
                ->sum('amount');
                
            $expenseData[] = $dayExpense;
            
            $currentDate->addDay();
        }
            
        return view('reports.financial', [
            'entries' => $entries,
            'sales' => $sales,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'totalSalesIncome' => $totalSalesIncome,
            'totalOtherIncome' => $totalOtherIncome,
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
            'expensesByCategory' => $expensesByCategory,
            'categories' => $categories,
            'selectedType' => $selectedType,
            'selectedCategory' => $selectedCategory,
            'chartLabels' => $chartLabels,
            'incomeData' => $incomeData,
            'expenseData' => $expenseData,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'totalExpense' => $totalExpenses,
            'netBalance' => $netProfit,
            'title' => 'Financial Report',
            'subtitle' => 'Income, expenses and profit'
        ]);
    }

    /**
     * Display the customer dues report.
     */
    public function customerDues(Request $request)
    {
        $shopId = session('current_shop_id');
        
        // Get selected customer if provided
        $selectedCustomer = $request->customer_id ?? null;
        
        // Get customers with pending dues
        $query = Customer::where('shop_id', $shopId)
            ->where('is_walk_in', false)
            ->whereHas('sales', function ($q) {
                $q->where('status', '!=', 'paid');
            });
            
        // Filter by customer if provided
        if ($selectedCustomer) {
            $query->where('id', $selectedCustomer);
        }
            
        $customers = $query->withCount('sales')
            ->withSum(['sales as total_due' => function ($q) {
                $q->where('status', '!=', 'paid');
            }], 'due_amount')
            ->with(['sales as pendingSales' => function($q) {
                $q->where('status', '!=', 'paid')
                  ->orderBy('sale_date', 'desc');
            }])
            ->orderByDesc('total_due')
            ->paginate(15);
            
        // Calculate total dues
        $totalDues = Sale::where('shop_id', $shopId)
            ->where('status', '!=', 'paid')
            ->sum('due_amount');
            
        // Get all customers for filter dropdown
        $allCustomers = Customer::where('shop_id', $shopId)
            ->where('is_walk_in', false)
            ->whereHas('sales', function ($q) {
                $q->where('status', '!=', 'paid');
            })
            ->orderBy('name')
            ->get();
            
        return view('reports.customer-dues', [
            'customers' => $customers,
            'allCustomers' => $allCustomers,
            'selectedCustomer' => $selectedCustomer,
            'totalDueAmount' => $totalDues,
            'title' => 'Customer Dues Report',
            'subtitle' => 'Outstanding payments from customers'
        ]);
    }

    /**
     * Display the bag weights report.
     */
    public function bagWeights(Request $request)
    {
        $shopId = session('current_shop_id');
        
        // Get all products with bag weights
        $products = Product::where('shop_id', $shopId)
            ->whereNotNull('avg_bag_weight')
            ->orderBy('name')
            ->get();
            
        // Get recent stock ins with bag weights
        $stockIns = StockIn::where('shop_id', $shopId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
            
        return view('reports.bag-weights', [
            'products' => $products,
            'stockIns' => $stockIns,
            'title' => 'Bag Weights Report',
            'subtitle' => 'Average weights and trends'
        ]);
    }

    /**
     * Export report data to Excel.
     */
    public function export($type, Request $request)
    {
        $shopId = session('current_shop_id');
        
        // Create new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set shop name in header
        $shopName = session('current_shop_name');
        $sheet->setCellValue('A1', $shopName . ' - ' . ucfirst($type) . ' Report');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        // Set report date
        $sheet->setCellValue('A2', 'Generated on: ' . now()->format('Y-m-d H:i'));
        $sheet->mergeCells('A2:F2');
        
        // Export based on report type
        switch ($type) {
            case 'stock':
                $this->exportStockReport($sheet, $shopId, $request);
                break;
                
            case 'discrepancy':
                $this->exportDiscrepancyReport($sheet, $shopId, $request);
                break;
                
            case 'financial':
                $this->exportFinancialReport($sheet, $shopId, $request);
                break;
                
            case 'customer-dues':
                $this->exportCustomerDuesReport($sheet, $shopId, $request);
                break;
                
            case 'bag-weights':
                $this->exportBagWeightsReport($sheet, $shopId, $request);
                break;
                
            default:
                return redirect()->back()->with('error', 'Invalid report type.');
        }
        
        // Create writer and output file
        $writer = new Xlsx($spreadsheet);
        $filename = $type . '_report_' . now()->format('Y-m-d') . '.xlsx';
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Helper method to export stock report.
     */
    private function exportStockReport($sheet, $shopId, $request)
    {
        // Set headers
        $sheet->setCellValue('A4', 'Product');
        $sheet->setCellValue('B4', 'Current Stock');
        $sheet->setCellValue('C4', 'Unit');
        $sheet->setCellValue('D4', 'Min Level');
        $sheet->setCellValue('E4', 'Avg Cost');
        $sheet->setCellValue('F4', 'Total Value');
        $sheet->getStyle('A4:F4')->getFont()->setBold(true);
        
        // Get products
        $query = Product::where('shop_id', $shopId);
        
        // Apply filters if provided
        if ($request->has('product_id')) {
            $query->where('id', $request->product_id);
        }
        
        if ($request->has('stock_level')) {
            if ($request->stock_level === 'low') {
                $query->where('current_stock', '>', 0)
                    ->where('current_stock', '<=', DB::raw('min_stock_level'));
            } elseif ($request->stock_level === 'out') {
                $query->where('current_stock', '<=', 0);
            } elseif ($request->stock_level === 'normal') {
                $query->where('current_stock', '>', DB::raw('min_stock_level'));
            }
        }
        
        $products = $query->orderBy('name')->get();
        
        // Add data rows
        $row = 5;
        $totalValue = 0;
        
        foreach ($products as $product) {
            $value = $product->current_stock * $product->avg_cost;
            $totalValue += $value;
            
            $sheet->setCellValue('A' . $row, $product->name);
            $sheet->setCellValue('B' . $row, $product->current_stock);
            $sheet->setCellValue('C' . $row, $product->unit);
            $sheet->setCellValue('D' . $row, $product->min_stock_level);
            $sheet->setCellValue('E' . $row, $product->avg_cost);
            $sheet->setCellValue('F' . $row, $value);
            
            $row++;
        }
        
        // Add total row
        $sheet->setCellValue('A' . $row, 'Total');
        $sheet->setCellValue('F' . $row, $totalValue);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        
        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
    
    /**
     * Helper method to export discrepancy report.
     */
    private function exportDiscrepancyReport($sheet, $shopId, $request)
    {
        // Set headers
        $sheet->setCellValue('A4', 'Date');
        $sheet->setCellValue('B4', 'Check Type');
        $sheet->setCellValue('C4', 'Product');
        $sheet->setCellValue('D4', 'System Stock');
        $sheet->setCellValue('E4', 'Physical Stock');
        $sheet->setCellValue('F4', 'Discrepancy');
        $sheet->setCellValue('G4', 'Discrepancy %');
        $sheet->setCellValue('H4', 'Notes');
        $sheet->setCellValue('I4', 'User');
        $sheet->getStyle('A4:I4')->getFont()->setBold(true);
        
        // Get date range
        $fromDate = $request->from_date ?? now()->subDays(30)->format('Y-m-d');
        $toDate = $request->to_date ?? now()->format('Y-m-d');
        
        // Get discrepancies
        $query = DailyStockCheck::where('shop_id', $shopId)
            ->where('discrepancy', '!=', 0)
            ->whereBetween('created_at', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
            
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        $discrepancies = $query->with(['product', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Add data rows
        $row = 5;
        
        foreach ($discrepancies as $check) {
            $sheet->setCellValue('A' . $row, $check->created_at->format('Y-m-d H:i'));
            $sheet->setCellValue('B' . $row, ucfirst($check->check_type));
            $sheet->setCellValue('C' . $row, $check->product->name);
            $sheet->setCellValue('D' . $row, $check->system_stock);
            $sheet->setCellValue('E' . $row, $check->physical_stock);
            $sheet->setCellValue('F' . $row, $check->discrepancy);
            $sheet->setCellValue('G' . $row, round($check->discrepancy_percent, 2) . '%');
            $sheet->setCellValue('H' . $row, $check->notes);
            $sheet->setCellValue('I' . $row, $check->user->name);
            
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
    
    /**
     * Helper method to export financial report.
     */
    private function exportFinancialReport($sheet, $shopId, $request)
    {
        // Get date range
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->format('Y-m-d');
        
        // Set date range in header
        $sheet->setCellValue('A3', 'Period: ' . $fromDate . ' to ' . $toDate);
        $sheet->mergeCells('A3:F3');
        
        // Set headers for income section
        $sheet->setCellValue('A5', 'INCOME');
        $sheet->getStyle('A5')->getFont()->setBold(true);
        
        $sheet->setCellValue('A6', 'Date');
        $sheet->setCellValue('B6', 'Type');
        $sheet->setCellValue('C6', 'Category');
        $sheet->setCellValue('D6', 'Description');
        $sheet->setCellValue('E6', 'Reference');
        $sheet->setCellValue('F6', 'Amount');
        $sheet->getStyle('A6:F6')->getFont()->setBold(true);
        
        // Get sales income
        $sales = Sale::where('shop_id', $shopId)
            ->whereBetween('sale_date', [$fromDate, $toDate])
            ->with('customer')
            ->get();
            
        // Get other income
        $incomeEntries = FinancialEntry::where('shop_id', $shopId)
            ->where('type', 'income')
            ->whereBetween('entry_date', [$fromDate, $toDate])
            ->with('category')
            ->orderBy('entry_date')
            ->get();
            
        // Add sales rows
        $row = 7;
        $totalSalesIncome = 0;
        
        foreach ($sales as $sale) {
            $sheet->setCellValue('A' . $row, $sale->sale_date);
            $sheet->setCellValue('B' . $row, 'Sale');
            $sheet->setCellValue('C' . $row, 'Sales Revenue');
            $sheet->setCellValue('D' . $row, 'Sale #' . $sale->id . ' - ' . $sale->customer->name);
            $sheet->setCellValue('E' . $row, '');
            $sheet->setCellValue('F' . $row, $sale->paid_amount);
            
            $totalSalesIncome += $sale->paid_amount;
            $row++;
        }
        
        // Add other income rows
        $totalOtherIncome = 0;
        
        foreach ($incomeEntries as $entry) {
            $sheet->setCellValue('A' . $row, $entry->entry_date);
            $sheet->setCellValue('B' . $row, 'Income');
            $sheet->setCellValue('C' . $row, $entry->category->name);
            $sheet->setCellValue('D' . $row, $entry->description);
            $sheet->setCellValue('E' . $row, $entry->reference);
            $sheet->setCellValue('F' . $row, $entry->amount);
            
            $totalOtherIncome += $entry->amount;
            $row++;
        }
        
        // Add income summary
        $row++;
        $sheet->setCellValue('A' . $row, 'Total Sales Income:');
        $sheet->setCellValue('F' . $row, $totalSalesIncome);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Total Other Income:');
        $sheet->setCellValue('F' . $row, $totalOtherIncome);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'TOTAL INCOME:');
        $sheet->setCellValue('F' . $row, $totalSalesIncome + $totalOtherIncome);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        
        // Set headers for expense section
        $row += 2;
        $sheet->setCellValue('A' . $row, 'EXPENSES');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Date');
        $sheet->setCellValue('B' . $row, 'Type');
        $sheet->setCellValue('C' . $row, 'Category');
        $sheet->setCellValue('D' . $row, 'Description');
        $sheet->setCellValue('E' . $row, 'Reference');
        $sheet->setCellValue('F' . $row, 'Amount');
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        
        // Get expenses
        $expenseEntries = FinancialEntry::where('shop_id', $shopId)
            ->where('type', 'expense')
            ->whereBetween('entry_date', [$fromDate, $toDate])
            ->with('category')
            ->orderBy('entry_date')
            ->get();
            
        // Add expense rows
        $row++;
        $totalExpenses = 0;
        
        foreach ($expenseEntries as $entry) {
            $sheet->setCellValue('A' . $row, $entry->entry_date);
            $sheet->setCellValue('B' . $row, 'Expense');
            $sheet->setCellValue('C' . $row, $entry->category->name);
            $sheet->setCellValue('D' . $row, $entry->description);
            $sheet->setCellValue('E' . $row, $entry->reference);
            $sheet->setCellValue('F' . $row, $entry->amount);
            
            $totalExpenses += $entry->amount;
            $row++;
        }
        
        // Add expense summary
        $row++;
        $sheet->setCellValue('A' . $row, 'TOTAL EXPENSES:');
        $sheet->setCellValue('F' . $row, $totalExpenses);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        
        // Add profit summary
        $row += 2;
        $sheet->setCellValue('A' . $row, 'NET PROFIT:');
        $sheet->setCellValue('F' . $row, ($totalSalesIncome + $totalOtherIncome) - $totalExpenses);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        
        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
    
    /**
     * Helper method to export customer dues report.
     */
    private function exportCustomerDuesReport($sheet, $shopId, $request)
    {
        // Set headers
        $sheet->setCellValue('A4', 'Customer');
        $sheet->setCellValue('B4', 'Phone');
        $sheet->setCellValue('C4', 'Pending Sales');
        $sheet->setCellValue('D4', 'Total Due Amount');
        $sheet->getStyle('A4:D4')->getFont()->setBold(true);
        
        // Get customers with dues
        $customers = Customer::where('shop_id', $shopId)
            ->where('is_walk_in', false)
            ->whereHas('sales', function ($q) {
                $q->where('status', '!=', 'paid');
            })
            ->withCount(['sales' => function ($q) {
                $q->where('status', '!=', 'paid');
            }])
            ->withSum(['sales as total_due' => function ($q) {
                $q->where('status', '!=', 'paid');
            }], 'due_amount')
            ->orderByDesc('total_due')
            ->get();
            
        // Add data rows
        $row = 5;
        $totalDues = 0;
        
        foreach ($customers as $customer) {
            $sheet->setCellValue('A' . $row, $customer->name);
            $sheet->setCellValue('B' . $row, $customer->phone);
            $sheet->setCellValue('C' . $row, $customer->sales_count);
            $sheet->setCellValue('D' . $row, $customer->total_due);
            
            $totalDues += $customer->total_due;
            $row++;
        }
        
        // Add total row
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('D' . $row, $totalDues);
        $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
        
        // Auto-size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
    
    /**
     * Helper method to export bag weights report.
     */
    private function exportBagWeightsReport($sheet, $shopId, $request)
    {
        // Set headers
        $sheet->setCellValue('A4', 'Product');
        $sheet->setCellValue('B4', 'Average Bag Weight');
        $sheet->setCellValue('C4', 'Total Bags');
        $sheet->getStyle('A4:C4')->getFont()->setBold(true);
        
        // Get products with bag weights
        $products = Product::where('shop_id', $shopId)
            ->whereNotNull('avg_bag_weight')
            ->orderBy('name')
            ->get();
            
        // Add data rows
        $row = 5;
        
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $product->name);
            $sheet->setCellValue('B' . $row, $product->avg_bag_weight);
            $sheet->setCellValue('C' . $row, $product->total_bags);
            
            $row++;
        }
        
        // Add recent stock ins section
        $row += 2;
        $sheet->setCellValue('A' . $row, 'RECENT STOCK INS');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Date');
        $sheet->setCellValue('B' . $row, 'Product');
        $sheet->setCellValue('C' . $row, 'Quantity');
        $sheet->setCellValue('D' . $row, 'Bags');
        $sheet->setCellValue('E' . $row, 'Bag Weight');
        $sheet->getStyle('A' . $row . ':E' . $row)->getFont()->setBold(true);
        
        // Get recent stock ins
        $stockIns = StockIn::where('shop_id', $shopId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
            
        // Add stock in rows
        $row++;
        
        foreach ($stockIns as $stockIn) {
            $sheet->setCellValue('A' . $row, $stockIn->created_at->format('Y-m-d'));
            $sheet->setCellValue('B' . $row, $stockIn->product->name);
            $sheet->setCellValue('C' . $row, $stockIn->quantity);
            $sheet->setCellValue('D' . $row, $stockIn->bags);
            $sheet->setCellValue('E' . $row, $stockIn->avg_bag_weight);
            
            $row++;
        }
        
        // Auto-size columns
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}