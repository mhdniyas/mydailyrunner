<?php

namespace App\Exports;

use App\Models\FinancialEntry;
use App\Models\Sale;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FinancialReportExport implements WithMultipleSheets
{
    protected $shopId;
    protected $fromDate;
    protected $toDate;

    public function __construct($shopId, $fromDate, $toDate)
    {
        $this->shopId = $shopId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        
        // Income sheet
        $sheets[] = new FinancialIncomeSheet($this->shopId, $this->fromDate, $this->toDate);
        
        // Expense sheet
        $sheets[] = new FinancialExpenseSheet($this->shopId, $this->fromDate, $this->toDate);
        
        // Summary sheet
        $sheets[] = new FinancialSummarySheet($this->shopId, $this->fromDate, $this->toDate);
        
        return $sheets;
    }
}

class FinancialIncomeSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    use \Maatwebsite\Excel\Concerns\FromCollection;
    use \Maatwebsite\Excel\Concerns\WithHeadings;
    use \Maatwebsite\Excel\Concerns\WithMapping;
    use \Maatwebsite\Excel\Concerns\WithTitle;
    use \Maatwebsite\Excel\Concerns\ShouldAutoSize;
    use \Maatwebsite\Excel\Concerns\WithStyles;
    
    protected $shopId;
    protected $fromDate;
    protected $toDate;

    public function __construct($shopId, $fromDate, $toDate)
    {
        $this->shopId = $shopId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Get sales income
        $sales = Sale::where('shop_id', $this->shopId)
            ->whereBetween('sale_date', [$this->fromDate, $this->toDate])
            ->with('customer')
            ->get();
            
        // Get other income
        $incomeEntries = FinancialEntry::where('shop_id', $this->shopId)
            ->where('type', 'income')
            ->whereBetween('entry_date', [$this->fromDate, $this->toDate])
            ->with('category')
            ->get();
            
        // Combine into one collection
        $salesCollection = $sales->map(function ($sale) {
            return [
                'date' => $sale->sale_date,
                'type' => 'Sale',
                'category' => 'Sales Revenue',
                'description' => 'Sale #' . $sale->id . ' - ' . $sale->customer->name,
                'reference' => '',
                'amount' => $sale->paid_amount,
            ];
        });
        
        $incomeCollection = $incomeEntries->map(function ($entry) {
            return [
                'date' => $entry->entry_date,
                'type' => 'Income',
                'category' => $entry->category->name,
                'description' => $entry->description,
                'reference' => $entry->reference,
                'amount' => $entry->amount,
            ];
        });
        
        return $salesCollection->concat($incomeCollection)->sortBy('date');
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Type',
            'Category',
            'Description',
            'Reference',
            'Amount',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row['date']->format('Y-m-d'),
            $row['type'],
            $row['category'],
            $row['description'],
            $row['reference'],
            $row['amount'],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Income';
    }

    /**
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array
     */
    public function styles($sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class FinancialExpenseSheet implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    use \Maatwebsite\Excel\Concerns\FromCollection;
    use \Maatwebsite\Excel\Concerns\WithHeadings;
    use \Maatwebsite\Excel\Concerns\WithMapping;
    use \Maatwebsite\Excel\Concerns\WithTitle;
    use \Maatwebsite\Excel\Concerns\ShouldAutoSize;
    use \Maatwebsite\Excel\Concerns\WithStyles;
    
    protected $shopId;
    protected $fromDate;
    protected $toDate;

    public function __construct($shopId, $fromDate, $toDate)
    {
        $this->shopId = $shopId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return FinancialEntry::where('shop_id', $this->shopId)
            ->where('type', 'expense')
            ->whereBetween('entry_date', [$this->fromDate, $this->toDate])
            ->with('category')
            ->orderBy('entry_date')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Category',
            'Description',
            'Reference',
            'Amount',
        ];
    }

    /**
     * @param mixed $entry
     * @return array
     */
    public function map($entry): array
    {
        return [
            $entry->entry_date->format('Y-m-d'),
            $entry->category->name,
            $entry->description,
            $entry->reference,
            $entry->amount,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Expenses';
    }

    /**
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array
     */
    public function styles($sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

class FinancialSummarySheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    use \Maatwebsite\Excel\Concerns\FromCollection;
    use \Maatwebsite\Excel\Concerns\WithHeadings;
    use \Maatwebsite\Excel\Concerns\WithTitle;
    use \Maatwebsite\Excel\Concerns\ShouldAutoSize;
    use \Maatwebsite\Excel\Concerns\WithStyles;
    
    protected $shopId;
    protected $fromDate;
    protected $toDate;

    public function __construct($shopId, $fromDate, $toDate)
    {
        $this->shopId = $shopId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Calculate totals
        $totalSalesIncome = Sale::where('shop_id', $this->shopId)
            ->whereBetween('sale_date', [$this->fromDate, $this->toDate])
            ->sum('paid_amount');
            
        $totalOtherIncome = FinancialEntry::where('shop_id', $this->shopId)
            ->where('type', 'income')
            ->whereBetween('entry_date', [$this->fromDate, $this->toDate])
            ->sum('amount');
            
        $totalIncome = $totalSalesIncome + $totalOtherIncome;
        
        $totalExpenses = FinancialEntry::where('shop_id', $this->shopId)
            ->where('type', 'expense')
            ->whereBetween('entry_date', [$this->fromDate, $this->toDate])
            ->sum('amount');
            
        $netProfit = $totalIncome - $totalExpenses;
        
        // Create summary collection
        return collect([
            ['category' => 'Sales Income', 'amount' => $totalSalesIncome],
            ['category' => 'Other Income', 'amount' => $totalOtherIncome],
            ['category' => 'Total Income', 'amount' => $totalIncome],
            ['category' => 'Total Expenses', 'amount' => $totalExpenses],
            ['category' => 'Net Profit', 'amount' => $netProfit],
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Category',
            'Amount',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Summary';
    }

    /**
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return array
     */
    public function styles($sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
        ];
    }
}