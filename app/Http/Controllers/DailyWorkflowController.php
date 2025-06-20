<?php

namespace App\Http\Controllers;

use App\Models\DailyStockCheck;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Shop;
use App\Models\StockBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyWorkflowController extends Controller
{
    /**
     * Display the daily workflow dashboard.
     */
    public function index()
    {
        $shopId = session('current_shop_id');
        $shop = Shop::findOrFail($shopId);
        
        // Get yesterday's date
        $yesterday = Carbon::yesterday();
        
        // Get yesterday's sales summary
        $yesterdaySales = Sale::where('shop_id', $shopId)
            ->whereDate('created_at', $yesterday)
            ->with('items.product')
            ->get();
            
        $yesterdayTotal = $yesterdaySales->sum('total_amount');
        $yesterdayCount = $yesterdaySales->count();
        
        // Get products with current stock levels
        $products = Product::where('shop_id', $shopId)
            ->orderBy('name')
            ->with(['batches' => function($q) {
                $q->latest('batch_date');
            }])
            ->get();
            
        // Check if stock check already done today
        $todayStockCheckExists = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', now())
            ->exists();
            
        // Get bag averages for each product
        $bagAverages = [];
        foreach ($products as $product) {
            $bagAverages[$product->id] = $product->calculateWeightedBagAverage();
        }
        
        // Get yesterday's discrepancies if any
        $yesterdayDiscrepancies = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', $yesterday)
            ->where('discrepancy', '!=', 0)
            ->with('product')
            ->get();
            
        return view('daily-workflow.index', [
            'shop' => $shop,
            'yesterdaySales' => $yesterdaySales,
            'yesterdayTotal' => $yesterdayTotal,
            'yesterdayCount' => $yesterdayCount,
            'products' => $products,
            'bagAverages' => $bagAverages,
            'todayStockCheckExists' => $todayStockCheckExists,
            'yesterdayDiscrepancies' => $yesterdayDiscrepancies,
            'title' => 'Daily Workflow',
            'subtitle' => 'Streamlined daily operations'
        ]);
    }
    
    /**
     * Show the form for recording physical stock.
     */
    public function recordStock()
    {
        $shopId = session('current_shop_id');
        
        // Check if stock check already done today
        $todayStockCheckExists = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', now())
            ->exists();
            
        if ($todayStockCheckExists) {
            return redirect()->route('daily-workflow.index')
                ->with('warning', 'Stock check has already been recorded today.');
        }
        
        // Get products with current stock levels
        $products = Product::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        // Get bag averages for each product
        $bagAverages = [];
        foreach ($products as $product) {
            $bagAverages[$product->id] = $product->calculateWeightedBagAverage();
        }
        
        return view('daily-workflow.record-stock', [
            'products' => $products,
            'bagAverages' => $bagAverages,
            'title' => 'Record Physical Stock',
            'subtitle' => 'Enter today\'s physical inventory count'
        ]);
    }
    
    /**
     * Store physical stock records and show discrepancy.
     */
    public function storeStock(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'system_stock.*' => 'required|numeric|min:0',
            'physical_stock.*' => 'required|numeric|min:0',
        ]);
        
        $stockChecks = [];
        $productIds = $request->input('product_id');
        $systemStocks = $request->input('system_stock');
        $physicalStocks = $request->input('physical_stock');
        
        foreach ($productIds as $index => $productId) {
            $product = Product::find($productId);
            $systemStock = $systemStocks[$index];
            $physicalStock = $physicalStocks[$index];
            
            // Calculate discrepancy
            $discrepancy = $physicalStock - $systemStock;
            $discrepancyPercent = $systemStock > 0 ? ($discrepancy / $systemStock) * 100 : 0;
            
            // Create stock check record
            $stockCheck = DailyStockCheck::create([
                'shop_id' => $shopId,
                'product_id' => $productId,
                'check_type' => 'morning',
                'system_stock' => $systemStock,
                'physical_stock' => $physicalStock,
                'discrepancy' => $discrepancy,
                'discrepancy_percent' => $discrepancyPercent,
                'user_id' => Auth::id(),
            ]);
            
            $stockChecks[] = $stockCheck;
        }
        
        return redirect()->route('daily-workflow.discrepancy')
            ->with('success', 'Physical stock recorded successfully.');
    }
    
    /**
     * Show discrepancy report and resolution form.
     */
    public function discrepancy()
    {
        $shopId = session('current_shop_id');
        
        // Get today's stock checks with discrepancies
        $stockChecks = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', now())
            ->where('discrepancy', '!=', 0)
            ->with('product')
            ->get();
            
        if ($stockChecks->isEmpty()) {
            return redirect()->route('daily-workflow.index')
                ->with('info', 'No discrepancies found in today\'s stock check.');
        }
        
        return view('daily-workflow.discrepancy', [
            'stockChecks' => $stockChecks,
            'title' => 'Discrepancy Report',
            'subtitle' => 'Review and resolve inventory discrepancies'
        ]);
    }
    
    /**
     * Store discrepancy resolution.
     */
    public function storeDiscrepancy(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'stock_check_id.*' => 'required|exists:daily_stock_checks,id',
            'reason.*' => 'required|string|max:255',
            'notes.*' => 'nullable|string|max:255',
        ]);
        
        $stockCheckIds = $request->input('stock_check_id');
        $reasons = $request->input('reason');
        $notes = $request->input('notes');
        
        foreach ($stockCheckIds as $index => $stockCheckId) {
            $stockCheck = DailyStockCheck::find($stockCheckId);
            
            // Ensure the stock check belongs to the current shop
            if ($stockCheck->shop_id != $shopId) {
                continue;
            }
            
            // Update the stock check with reason and notes
            $stockCheck->update([
                'notes' => ($reasons[$index] ?? 'Unknown') . ': ' . ($notes[$index] ?? ''),
            ]);
            
            // Update the product's stock level to match physical count
            $product = $stockCheck->product;
            $product->update([
                'current_stock' => $stockCheck->physical_stock,
            ]);
        }
        
        return redirect()->route('daily-workflow.complete')
            ->with('success', 'Discrepancies resolved successfully.');
    }
    
    /**
     * Show completion page.
     */
    public function complete()
    {
        $shopId = session('current_shop_id');
        
        // Get today's stock checks
        $stockChecks = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', now())
            ->with('product')
            ->get();
            
        if ($stockChecks->isEmpty()) {
            return redirect()->route('daily-workflow.index')
                ->with('error', 'No stock checks found for today.');
        }
        
        return view('daily-workflow.complete', [
            'stockChecks' => $stockChecks,
            'completedBy' => Auth::user()->name,
            'completedAt' => now()->format('Y-m-d H:i:s'),
            'title' => 'Daily Check Complete',
            'subtitle' => 'Summary of today\'s inventory check'
        ]);
    }
}
