<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\DailyStockCheck;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailySalesController extends Controller
{
    public function index()
    {
        $shopId = session('current_shop_id');
        $today = Carbon::today();
        
        // Get all sales for today
        $sales = Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', $today)
            ->with(['items.product.category'])
            ->get();

        // Get morning stock checks for today
        $morningStockChecks = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', $today)
            ->where('check_type', 'morning')
            ->with('product')
            ->get()
            ->keyBy('product_id');

        // Get evening stock checks for today
        $eveningStockChecks = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', $today)
            ->where('check_type', 'evening')
            ->with('product')
            ->get()
            ->keyBy('product_id');

        // Get bag averages from the most recent stock in for each product
        $bagAverages = DB::table('stock_ins')
            ->select('product_id', 
                     DB::raw('MAX(id) as latest_id'))
            ->where('shop_id', $shopId)
            ->where('bags', '>', 0)  // Only include records with bags > 0
            ->groupBy('product_id');
            
        $latestStockIns = StockIn::with('product')
            ->joinSub($bagAverages, 'latest_stock_ins', function($join) {
                $join->on('stock_ins.id', '=', 'latest_stock_ins.latest_id');
            })
            ->get()
            ->keyBy('product_id');

        // Calculate totals by category
        $categoryTotals = [];
        $productSales = [];
        
        // First, gather all product sales
        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $productId = $item->product_id;
                
                if (!isset($productSales[$productId])) {
                    $productSales[$productId] = [
                        'product' => $item->product,
                        'quantity' => 0,
                        'amount' => 0
                    ];
                }
                
                $productSales[$productId]['quantity'] += $item->quantity;
                $productSales[$productId]['amount'] += $item->subtotal;
            }
        }
        
        // Then, organize by category and add stock checks and bag averages
        foreach ($productSales as $productId => $productData) {
            $product = $productData['product'];
            $categoryId = $product->category->id;
            $categoryName = $product->category->name;
            
            if (!isset($categoryTotals[$categoryId])) {
                $categoryTotals[$categoryId] = [
                    'name' => $categoryName,
                    'quantity' => 0,
                    'amount' => 0,
                    'products' => []
                ];
            }
            
            $categoryTotals[$categoryId]['quantity'] += $productData['quantity'];
            $categoryTotals[$categoryId]['amount'] += $productData['amount'];
            
            // Add morning and evening stock data if available
            $morningStock = $morningStockChecks->get($productId);
            $eveningStock = $eveningStockChecks->get($productId);
            
            // Get bag average if available from the latest stock in
            $stockIn = $latestStockIns->get($productId);
            $bagAverage = $stockIn ? $stockIn->getActualBagWeight() : null;
            
            $categoryTotals[$categoryId]['products'][$productId] = [
                'name' => $product->name,
                'quantity' => $productData['quantity'],
                'amount' => $productData['amount'],
                'morning_stock' => $morningStock ? $morningStock->physical_stock : null,
                'evening_stock' => $eveningStock ? $eveningStock->physical_stock : null,
                'system_stock' => $eveningStock ? $eveningStock->system_stock : ($morningStock ? $morningStock->system_stock : null),
                'morning_discrepancy' => $morningStock ? $morningStock->discrepancy : null,
                'evening_discrepancy' => $eveningStock ? $eveningStock->discrepancy : null,
                'bag_average' => $bagAverage,
                'calculated_bags' => $bagAverage && $bagAverage > 0 ? 
                    round($productData['quantity'] / $bagAverage, 1) : null
            ];
        }

        // Calculate overall totals
        $totalSales = $sales->count();
        $totalAmount = $sales->sum('total_amount');
        $totalPaid = $sales->sum('paid_amount');
        $totalDue = $sales->sum('due_amount');

        return view('daily-sales.index', [
            'sales' => $sales,
            'categoryTotals' => $categoryTotals,
            'totalSales' => $totalSales,
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue,
            'date' => $today
        ]);
    }
}
