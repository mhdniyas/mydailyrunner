<?php

namespace App\Http\Controllers;

use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialCategoryController extends Controller
{
    /**
     * Store a newly created financial category.
     */
    public function store(Request $request)
    {
        $shopId = session('current_shop_id');
        
        // Ensure shop is selected
        if (!$shopId) {
            return response()->json([
                'success' => false,
                'message' => 'No shop selected. Please select a shop first.'
            ], 400);
        }
        
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|in:income,expense',
                'description' => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
         // Check if user has permission to create financial categories
        if (!auth()->user()->hasAnyRole(['owner', 'manager', 'finance'], $shopId)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to create financial categories.'
            ], 403);
        }

        // Verify the shop belongs to the user
        $userShop = auth()->user()->shops()->where('shops.id', $shopId)->first();
        if (!$userShop) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this shop.'
            ], 403);
        }
        
        // Check if category name already exists for this shop and type
        $existingCategory = FinancialCategory::where('shop_id', $shopId)
            ->where('type', $validated['type'])
            ->where('name', $validated['name'])
            ->first();
            
        if ($existingCategory) {
            return response()->json([
                'success' => false,
                'message' => 'A ' . $validated['type'] . ' category with this name already exists.'
            ], 422);
        }
        
        // Create financial category
        $category = FinancialCategory::create([
            'shop_id' => $shopId,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'user_id' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => ucfirst($validated['type']) . ' category created successfully.',
            'category' => [
                'id' => $category->id,
                'name' => $category->name,
                'type' => $category->type
            ]
        ]);
    }
}
