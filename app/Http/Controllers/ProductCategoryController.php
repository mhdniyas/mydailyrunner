<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $shopId = session('current_shop_id');
        $categories = ProductCategory::where('shop_id', $shopId)
            ->orderBy('name')
            ->paginate(10);
            
        return view('product-categories.index', [
            'categories' => $categories,
            'title' => 'Product Categories',
            'subtitle' => 'Manage your product categories'
        ]);
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('product-categories.create', [
            'title' => 'Add Category',
            'subtitle' => 'Create a new product category'
        ]);
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Verify the shop belongs to the user
        $userShop = auth()->user()->shops()->where('shops.id', $shopId)->first();
        if (!$userShop) {
            return redirect()->route('product-categories.index')
                ->with('error', 'You do not have access to this shop.');
        }
        
        ProductCategory::create([
            'shop_id' => $shopId,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->route('product-categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(ProductCategory $productCategory)
    {
        $shopId = session('current_shop_id');
        
        // Check if category belongs to the current shop
        if ($productCategory->shop_id !== $shopId) {
            return redirect()->route('product-categories.index')
                ->with('error', 'You do not have access to this category.');
        }
        
        // Get products in this category
        $products = $productCategory->products()->orderBy('name')->paginate(10);
        
        return view('product-categories.show', [
            'category' => $productCategory,
            'products' => $products,
            'title' => $productCategory->name,
            'subtitle' => 'Category details and products'
        ]);
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(ProductCategory $productCategory)
    {
        $shopId = session('current_shop_id');
        
        // Check if category belongs to the current shop
        if ($productCategory->shop_id !== $shopId) {
            return redirect()->route('product-categories.index')
                ->with('error', 'You do not have access to this category.');
        }
        
        return view('product-categories.edit', [
            'category' => $productCategory,
            'title' => 'Edit Category',
            'subtitle' => 'Update category details'
        ]);
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, ProductCategory $productCategory)
    {
        $shopId = session('current_shop_id');
        
        // Check if category belongs to the current shop
        if ($productCategory->shop_id !== $shopId) {
            return redirect()->route('product-categories.index')
                ->with('error', 'You do not have access to this category.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $productCategory->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
        
        return redirect()->route('product-categories.show', $productCategory)
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        $shopId = session('current_shop_id');
        
        // Check if category belongs to the current shop
        if ($productCategory->shop_id !== $shopId) {
            return redirect()->route('product-categories.index')
                ->with('error', 'You do not have access to this category.');
        }
        
        // Set category_id to null for all products in this category
        $productCategory->products()->update(['category_id' => null]);
        
        $productCategory->delete();
        
        return redirect()->route('product-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
