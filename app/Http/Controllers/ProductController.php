<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $query = Product::where('shop_id', $shopId);
        
        // Search by name if provided
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $products = $query->orderBy('name')
            ->paginate(15);
            
        return view('products.index', [
            'products' => $products,
            'search' => $request->search ?? '',
            'title' => 'Products',
            'subtitle' => 'Manage your product catalog'
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.form', [
            'product' => null,
            'title' => 'Add Product',
            'subtitle' => 'Create a new product'
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'min_stock_level' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'avg_cost' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
        ]);
        
        // Create product
        $product = Product::create([
            'shop_id' => $shopId,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'unit' => $validated['unit'],
            'min_stock_level' => $validated['min_stock_level'],
            'current_stock' => $validated['current_stock'],
            'avg_cost' => $validated['avg_cost'] ?? 0,
            'sale_price' => $validated['sale_price'] ?? 0,
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);
        
        // Load recent stock ins
        $stockIns = $product->stockIns()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Load recent sales
        $saleItems = $product->saleItems()
            ->with('sale')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Load recent stock checks
        $stockChecks = $product->stockChecks()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('products.show', [
            'product' => $product,
            'stockIns' => $stockIns,
            'saleItems' => $saleItems,
            'stockChecks' => $stockChecks,
            'title' => 'Product Details',
            'subtitle' => 'View product information'
        ]);
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        return view('products.form', [
            'product' => $product,
            'title' => 'Edit Product',
            'subtitle' => 'Modify product information'
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:50',
            'min_stock_level' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'avg_cost' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
        ]);
        
        // Update product
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'unit' => $validated['unit'],
            'min_stock_level' => $validated['min_stock_level'],
            'current_stock' => $validated['current_stock'],
            'avg_cost' => $validated['avg_cost'] ?? 0,
            'sale_price' => $validated['sale_price'] ?? 0,
        ]);
        
        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        
        // Check if product has related records
        if ($product->stockIns()->count() > 0 || $product->saleItems()->count() > 0 || $product->stockChecks()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete product with related records. Consider deactivating it instead.');
        }
        
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}