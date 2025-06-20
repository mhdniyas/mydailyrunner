<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockIn;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockInController extends Controller
{
    /**
     * Display a listing of the stock ins.
     */
    public function index()
    {
        $shopId = session('current_shop_id');
        $stockIns = StockIn::where('shop_id', $shopId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('stock-ins.index', [
            'stockIns' => $stockIns,
            'title' => 'Stock In Records',
            'subtitle' => 'Manage your inventory additions'
        ]);
    }

    /**
     * Show the form for creating a new stock in.
     */
    public function create()
    {
        $shopId = session('current_shop_id');
        $products = Product::where('shop_id', $shopId)->orderBy('name')->get();
        
        return view('stock-ins.create', [
            'products' => $products,
            'title' => 'Add Stock',
            'subtitle' => 'Record new inventory with batch information'
        ]);
    }

    /**
     * Store a newly created stock in in storage.
     */
    public function store(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.1',
            'bags' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'calculation_method' => 'required|in:formula_minus_half,formula_direct,manual',
            'manual_bag_weight' => 'nullable|numeric|min:0|required_if:calculation_method,manual',
            'notes' => 'nullable|string|max:255',
            // New batch fields
            'batch_date' => 'nullable|date',
            'supplier' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after_or_equal:batch_date',
        ]);
        
        // Calculate average bag weight based on method
        $avgBagWeight = match($validated['calculation_method']) {
            'manual' => $validated['manual_bag_weight'],
            'formula_direct' => $validated['quantity'] / $validated['bags'],
            'formula_minus_half' => ($validated['quantity'] / $validated['bags']) - 0.5,
            default => ($validated['quantity'] / $validated['bags']) - 0.5
        };
        
        // Create stock in record
        $stockIn = StockIn::create([
            'shop_id' => $shopId,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'bags' => $validated['bags'],
            'cost' => $validated['cost'],
            'avg_bag_weight' => $avgBagWeight,
            'calculation_method' => $validated['calculation_method'],
            'manual_bag_weight' => $validated['manual_bag_weight'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'user_id' => Auth::id(),
        ]);
        
        // Create batch record
        $stockIn->createBatch([
            'batch_date' => $validated['batch_date'] ?? now(),
            'supplier' => $validated['supplier'] ?? null,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);
        
        // Update product's average cost and bag weight
        $product = Product::find($validated['product_id']);
        
        // Update average cost calculation
        $totalOldValue = $product->current_stock * $product->avg_cost;
        $newValue = $validated['cost'];
        $newTotalStock = $product->current_stock + $validated['quantity'];
        
        if ($newTotalStock > 0) {
            $newAvgCost = ($totalOldValue + $newValue) / $newTotalStock;
            $product->avg_cost = $newAvgCost;
        }
        
        // Update average bag weight (weighted average)
        if ($product->avg_bag_weight) {
            $totalOldBags = $product->total_bags ?? 0;
            $newTotalBags = $totalOldBags + $validated['bags'];
            
            $weightedAvgBagWeight = (($product->avg_bag_weight * $totalOldBags) + 
                                    ($avgBagWeight * $validated['bags'])) / $newTotalBags;
            
            $product->avg_bag_weight = $weightedAvgBagWeight;
            $product->total_bags = $newTotalBags;
        } else {
            $product->avg_bag_weight = $avgBagWeight;
            $product->total_bags = $validated['bags'];
        }
        
        // Update current stock
        $product->current_stock += $validated['quantity'];
        $product->save();
        
        return redirect()->route('stock-ins.index')
            ->with('success', 'Stock added successfully.');
    }

    /**
     * Display the specified stock in.
     */
    public function show(StockIn $stockIn)
    {
        $this->authorize('view', $stockIn);
        
        return view('stock-ins.show', [
            'stockIn' => $stockIn->load('product', 'user'),
            'title' => 'Stock In Details',
            'subtitle' => 'View stock entry information'
        ]);
    }

    /**
     * Show the form for editing the specified stock in.
     */
    public function edit(StockIn $stockIn)
    {
        $this->authorize('update', $stockIn);
        
        $shopId = session('current_shop_id');
        $products = Product::where('shop_id', $shopId)->orderBy('name')->get();
        
        return view('stock-ins.edit', [
            'stockIn' => $stockIn,
            'products' => $products,
            'title' => 'Edit Stock Entry',
            'subtitle' => 'Modify stock information'
        ]);
    }

    /**
     * Update the specified stock in in storage.
     */
    public function update(Request $request, StockIn $stockIn)
    {
        $this->authorize('update', $stockIn);
        
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.1',
            'bags' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
            'calculation_method' => 'required|in:formula_minus_half,formula_direct,manual',
            'manual_bag_weight' => 'nullable|numeric|min:0|required_if:calculation_method,manual',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Calculate average bag weight based on method
        $avgBagWeight = match($validated['calculation_method']) {
            'manual' => $validated['manual_bag_weight'],
            'formula_direct' => $validated['quantity'] / $validated['bags'],
            'formula_minus_half' => ($validated['quantity'] / $validated['bags']) - 0.5,
            default => ($validated['quantity'] / $validated['bags']) - 0.5
        };
        
        // Get the old values for product updates
        $oldProductId = $stockIn->product_id;
        $oldQuantity = $stockIn->quantity;
        $oldBags = $stockIn->bags;
        $oldCost = $stockIn->cost;
        
        // Update stock in record
        $stockIn->update([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'bags' => $validated['bags'],
            'cost' => $validated['cost'],
            'avg_bag_weight' => $avgBagWeight,
            'calculation_method' => $validated['calculation_method'],
            'manual_bag_weight' => $validated['manual_bag_weight'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);
        
        // Handle product updates - first revert old changes
        if ($oldProductId == $validated['product_id']) {
            $product = Product::find($oldProductId);
            
            // Revert old stock
            $product->current_stock -= $oldQuantity;
            
            // Revert old bag count
            $product->total_bags -= $oldBags;
            
            // Apply new values
            $product->current_stock += $validated['quantity'];
            $product->total_bags += $validated['bags'];
            
            // Recalculate average bag weight
            if ($product->total_bags > 0) {
                $product->avg_bag_weight = $stockIn->avg_bag_weight;
            }
            
            $product->save();
        } else {
            // Handle product change - revert old product
            $oldProduct = Product::find($oldProductId);
            $oldProduct->current_stock -= $oldQuantity;
            $oldProduct->total_bags -= $oldBags;
            $oldProduct->save();
            
            // Update new product
            $newProduct = Product::find($validated['product_id']);
            $newProduct->current_stock += $validated['quantity'];
            $newProduct->total_bags += $validated['bags'];
            
            // Update average bag weight
            if ($newProduct->total_bags > 0) {
                $newProduct->avg_bag_weight = $stockIn->avg_bag_weight;
            }
            
            $newProduct->save();
        }
        
        return redirect()->route('stock-ins.index')
            ->with('success', 'Stock entry updated successfully.');
    }

    /**
     * Remove the specified stock in from storage.
     */
    public function destroy(StockIn $stockIn)
    {
        $this->authorize('delete', $stockIn);
        
        // Revert product stock changes
        $product = $stockIn->product;
        $product->current_stock -= $stockIn->quantity;
        $product->total_bags -= $stockIn->bags;
        $product->save();
        
        $stockIn->delete();
        
        return redirect()->route('stock-ins.index')
            ->with('success', 'Stock entry deleted successfully.');
    }
}