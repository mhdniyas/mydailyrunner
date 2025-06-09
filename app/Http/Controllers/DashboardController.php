<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\DailyStockCheck;
use App\Models\FinancialEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $shopId = session('current_shop_id');
        
        // If no shop selected, redirect to shop selection
        if (!$shopId) {
            return redirect()->route('shops.select');
        }
        
        // Get current stock value and quantity
        $totalStockValue = Product::where('shop_id', $shopId)
            ->sum(DB::raw('current_stock * avg_cost'));
            
        $totalStockQuantity = Product::where('shop_id', $shopId)
            ->sum('current_stock');
            
        // Get today's sales
        $todaySales = Sale::where('shop_id', $shopId)
            ->whereDate('sale_date', today())
            ->sum('paid_amount');
            
        // Get today's cash in/out
        $cashIn = FinancialEntry::where('shop_id', $shopId)
            ->where('type', 'income')
            ->whereDate('entry_date', today())
            ->sum('amount');
            
        $cashOut = FinancialEntry::where('shop_id', $shopId)
            ->where('type', 'expense')
            ->whereDate('entry_date', today())
            ->sum('amount');
            
        $dailyBalance = $cashIn - $cashOut;
            
        // Get low stock products
        $lowStockProducts = Product::where('shop_id', $shopId)
            ->where('current_stock', '>', 0)
            ->where('current_stock', '<=', DB::raw('min_stock_level'))
            ->orderBy('current_stock')
            ->limit(5)
            ->get();
            
        // Get out of stock products
        $outOfStockProducts = Product::where('shop_id', $shopId)
            ->where('current_stock', '<=', 0)
            ->orderBy('name')
            ->limit(5)
            ->get();
            
        // Get recent discrepancies
        $discrepancies = DailyStockCheck::where('shop_id', $shopId)
            ->where('discrepancy', '!=', 0)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get recent sales
        $recentSales = Sale::where('shop_id', $shopId)
            ->with('customer', 'items.product')
            ->orderBy('sale_date', 'desc')
            ->limit(5)
            ->get();
            
        // Get pending customer payments
        $pendingPayments = Sale::where('shop_id', $shopId)
            ->where('status', '!=', 'paid')
            ->with('customer')
            ->orderBy('due_amount', 'desc')
            ->limit(5)
            ->get();
            
        // Get monthly sales chart data
        $monthlySales = Sale::where('shop_id', $shopId)
            ->select(DB::raw('YEAR(sale_date) as year, MONTH(sale_date) as month, SUM(paid_amount) as total'))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->limit(12)
            ->get();
            
        $salesChartLabels = $monthlySales->map(function ($item) {
            return date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year));
        });
        
        $salesChartData = $monthlySales->pluck('total');
        
        // Get all products for reference
        $allProducts = Product::where('shop_id', $shopId)->get();
        
        return view('dashboard', [
            'totalStockValue' => $totalStockValue,
            'totalStockQuantity' => $totalStockQuantity,
            'todaySales' => $todaySales,
            'cashIn' => $cashIn,
            'cashOut' => $cashOut,
            'dailyBalance' => $dailyBalance,
            'lowStockProducts' => $lowStockProducts,
            'outOfStockProducts' => $outOfStockProducts,
            'discrepancies' => $discrepancies,
            'recentSales' => $recentSales,
            'pendingPayments' => $pendingPayments,
            'salesChartLabels' => $salesChartLabels,
            'salesChartData' => $salesChartData,
            'allProducts' => $allProducts,
            'title' => 'Dashboard',
            'subtitle' => 'Shop overview and analytics'
        ]);
    }
}