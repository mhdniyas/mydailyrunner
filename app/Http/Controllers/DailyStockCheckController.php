<?php

namespace App\Http\Controllers;

use App\Models\DailyStockCheck;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyStockCheckController extends Controller
{
    /**
     * Display a listing of the daily stock checks.
     */
    public function index()
    {
        $shopId = session('current_shop_id');
        
        // Get unique dates for stock checks
        $dates = DailyStockCheck::where('shop_id', $shopId)
            ->select(DB::raw('DATE(created_at) as check_date'))
            ->distinct()
            ->orderBy('check_date', 'desc')
            ->paginate(10);
            
        // For each date, get summary info
        $summaries = [];
        foreach ($dates as $date) {
            $checkDate = $date->check_date;
            
            // Get morning and evening checks for this date
            $morningCount = DailyStockCheck::where('shop_id', $shopId)
                ->whereDate('created_at', $checkDate)
                ->where('check_type', 'morning')
                ->count();
                
            $eveningCount = DailyStockCheck::where('shop_id', $shopId)
                ->whereDate('created_at', $checkDate)
                ->where('check_type', 'evening')
                ->count();
                
            // Get discrepancy count
            $discrepancyCount = DailyStockCheck::where('shop_id', $shopId)
                ->whereDate('created_at', $checkDate)
                ->where('discrepancy', '!=', 0)
                ->count();
                
            $summaries[$checkDate] = [
                'morning' => $morningCount > 0,
                'evening' => $eveningCount > 0,
                'discrepancies' => $discrepancyCount,
            ];
        }
        
        return view('daily-stock-checks.index', [
            'dates' => $dates,
            'summaries' => $summaries,
            'title' => 'Daily Stock Checks',
            'subtitle' => 'Track and verify your inventory'
        ]);
    }

    /**
     * Show the form for creating a new daily stock check.
     */
    public function create()
    {
        $shopId = session('current_shop_id');
        $products = Product::where('shop_id', $shopId)->orderBy('name')->get();
        
        // Check if morning check already done today
        $morningDone = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', now())
            ->where('check_type', 'morning')
            ->exists();
            
        // Check if evening check already done today
        $eveningDone = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', now())
            ->where('check_type', 'evening')
            ->exists();
            
        $checkType = !$morningDone ? 'morning' : (!$eveningDone ? 'evening' : null);
        
        return view('daily-stock-checks.create', [
            'products' => $products,
            'checkType' => $checkType,
            'morningDone' => $morningDone,
            'eveningDone' => $eveningDone,
            'title' => 'New Stock Check',
            'subtitle' => 'Record physical inventory count'
        ]);
    }
    
    /**
     * Show the form for creating a specific type of stock check.
     */
    public function createByType($type)
    {
        if (!in_array($type, ['morning', 'evening'])) {
            return redirect()->route('daily-stock-checks.create')
                ->with('error', 'Invalid check type specified.');
        }
        
        $shopId = session('current_shop_id');
        $products = Product::where('shop_id', $shopId)->orderBy('name')->get();
        
        // Check if this type already done today
        $alreadyDone = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', now())
            ->where('check_type', $type)
            ->exists();
            
        if ($alreadyDone) {
            return redirect()->route('daily-stock-checks.create')
                ->with('error', ucfirst($type) . ' check already completed today.');
        }
        
        return view('daily-stock-checks.create', [
            'products' => $products,
            'checkType' => $type,
            'title' => ucfirst($type) . ' Stock Check',
            'subtitle' => 'Record physical inventory count'
        ]);
    }

    /**
     * Store a newly created daily stock check in storage.
     */
    public function store(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'check_type' => 'required|in:morning,evening',
            'product_id.*' => 'required|exists:products,id',
            'system_stock.*' => 'required|numeric|min:0',
            'physical_stock.*' => 'required|numeric|min:0',
            'notes.*' => 'nullable|string|max:255',
        ]);
        
        // Check if this type already done today
        $alreadyDone = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', now())
            ->where('check_type', $validated['check_type'])
            ->exists();
            
        if ($alreadyDone) {
            return redirect()->route('daily-stock-checks.create')
                ->with('error', ucfirst($validated['check_type']) . ' check already completed today.');
        }
        
        // Verify the shop belongs to the user
        $userShop = auth()->user()->shops()->where('shops.id', $shopId)->first();
        if (!$userShop) {
            return redirect()->route('daily-stock-checks.index')
                ->with('error', 'You do not have access to this shop.');
        }
        
        // Process each product
        $productIds = $request->input('product_id');
        $systemStocks = $request->input('system_stock');
        $physicalStocks = $request->input('physical_stock');
        $notes = $request->input('notes');
        
        foreach ($productIds as $index => $productId) {
            $systemStock = $systemStocks[$index];
            $physicalStock = $physicalStocks[$index];
            $note = $notes[$index] ?? null;
            
            // Calculate discrepancy
            $discrepancy = $physicalStock - $systemStock;
            $discrepancyPercent = $systemStock > 0 ? ($discrepancy / $systemStock) * 100 : 0;
            
            // Create stock check record
            DailyStockCheck::create([
                'shop_id' => $shopId,
                'product_id' => $productId,
                'check_type' => $validated['check_type'],
                'system_stock' => $systemStock,
                'physical_stock' => $physicalStock,
                'discrepancy' => $discrepancy,
                'discrepancy_percent' => $discrepancyPercent,
                'notes' => $note,
                'user_id' => Auth::id(),
            ]);
            
            // Update product's current stock to match physical count
            $product = Product::find($productId);
            $product->current_stock = $physicalStock;
            $product->save();
        }
        
        return redirect()->route('daily-stock-checks.index')
            ->with('success', ucfirst($validated['check_type']) . ' stock check completed successfully.');
    }

    /**
     * Display the specified daily stock check.
     */
    public function show($date)
    {
        $shopId = session('current_shop_id');
        
        // Get morning checks
        $morningChecks = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', $date)
            ->where('check_type', 'morning')
            ->with('product', 'user')
            ->get();
            
        // Get evening checks
        $eveningChecks = DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', $date)
            ->where('check_type', 'evening')
            ->with('product', 'user')
            ->get();
            
        // Calculate summary stats
        $totalMorningDiscrepancy = $morningChecks->sum('discrepancy');
        $totalEveningDiscrepancy = $eveningChecks->sum('discrepancy');
        
        $morningDiscrepancyCount = $morningChecks->where('discrepancy', '!=', 0)->count();
        $eveningDiscrepancyCount = $eveningChecks->where('discrepancy', '!=', 0)->count();
        
        return view('daily-stock-checks.show', [
            'date' => $date,
            'morningChecks' => $morningChecks,
            'eveningChecks' => $eveningChecks,
            'totalMorningDiscrepancy' => $totalMorningDiscrepancy,
            'totalEveningDiscrepancy' => $totalEveningDiscrepancy,
            'morningDiscrepancyCount' => $morningDiscrepancyCount,
            'eveningDiscrepancyCount' => $eveningDiscrepancyCount,
            'title' => 'Stock Check: ' . $date,
            'subtitle' => 'Daily inventory verification results'
        ]);
    }

    /**
     * Remove the specified daily stock check.
     */
    public function destroy($date)
    {
        $shopId = session('current_shop_id');
        
        // Delete all checks for this date
        DailyStockCheck::where('shop_id', $shopId)
            ->whereDate('created_at', $date)
            ->delete();
            
        return redirect()->route('daily-stock-checks.index')
            ->with('success', 'Stock checks for ' . $date . ' deleted successfully.');
    }
}