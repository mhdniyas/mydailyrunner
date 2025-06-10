<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Shop;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $shopId = session('current_shop_id');
        $shop = Shop::findOrFail($shopId);
        
        $query = Customer::where('shop_id', $shopId);
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('ration_card_number', 'like', "%{$search}%");
            });
        }
        
        // Filter by card type
        if ($request->has('card_type') && !empty($request->card_type)) {
            $query->where('card_type', $request->card_type);
        }
        
        $customers = $query->orderBy('name')->paginate(15);
        
        return view('customers.index', [
            'customers' => $customers,
            'search' => $request->search ?? '',
            'cardType' => $request->card_type ?? '',
            'shop' => $shop
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shopId = session('current_shop_id');
        $shop = Shop::findOrFail($shopId);
        
        return view('customers.create', [
            'shop' => $shop
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'ration_card_number' => 'nullable|string|max:50',
            'card_type' => 'nullable|in:AAY,PHH,NPS,NPI,NPNS',
            'notes' => 'nullable|string',
            'is_walk_in' => 'boolean'
        ]);
        
        $validated['shop_id'] = $shopId;
        $validated['is_walk_in'] = $request->has('is_walk_in');
        $validated['user_id'] = auth()->id();
        
        // Verify the shop belongs to the user
        $userShop = auth()->user()->shops()->where('id', $shopId)->first();
        if (!$userShop) {
            abort(403, 'You do not have access to this shop.');
        }
        
        Customer::create($validated);
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $shopId = session('current_shop_id');
        $shop = Shop::findOrFail($shopId);
        
        // Check if customer belongs to current shop
        if ($customer->shop_id != $shopId) {
            abort(403, 'Unauthorized action.');
        }
        
        // Get customer's sales
        $sales = $customer->sales()->orderBy('sale_date', 'desc')->paginate(10);
        
        // Calculate total purchases and dues
        $totalPurchases = $customer->sales()->sum('total_amount');
        $totalDues = $customer->sales()->where('status', '!=', 'paid')->sum('due_amount');
        
        return view('customers.show', [
            'customer' => $customer,
            'sales' => $sales,
            'totalPurchases' => $totalPurchases,
            'totalDues' => $totalDues,
            'shop' => $shop
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        $shopId = session('current_shop_id');
        $shop = Shop::findOrFail($shopId);
        
        // Check if customer belongs to current shop
        if ($customer->shop_id != $shopId) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('customers.edit', [
            'customer' => $customer,
            'shop' => $shop
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $shopId = session('current_shop_id');
        
        // Check if customer belongs to current shop
        if ($customer->shop_id != $shopId) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'ration_card_number' => 'nullable|string|max:50',
            'card_type' => 'nullable|in:AAY,PHH,NPS,NPI,NPNS',
            'notes' => 'nullable|string',
            'is_walk_in' => 'boolean'
        ]);
        
        $validated['is_walk_in'] = $request->has('is_walk_in');
        
        $customer->update($validated);
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $shopId = session('current_shop_id');
        
        // Check if customer belongs to current shop
        if ($customer->shop_id != $shopId) {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if customer has sales
        if ($customer->sales()->count() > 0) {
            return redirect()->route('customers.index')
                ->with('error', 'Cannot delete customer with sales records.');
        }
        
        $customer->delete();
        
        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}