<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use App\Models\ShopUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    /**
     * Display a listing of the shops.
     */
    public function index()
    {
        // Only owner can see all shops
        $this->authorize('viewAny', Shop::class);
        
        $shops = Shop::withCount('users')
            ->orderBy('name')
            ->paginate(10);
            
        return view('shops.index', [
            'shops' => $shops,
            'title' => 'Shop Management',
            'subtitle' => 'Manage your business locations'
        ]);
    }

    /**
     * Show the form for creating a new shop.
     */
    public function create()
    {
        $this->authorize('create', Shop::class);
        
        return view('shops.create', [
            'title' => 'Add Shop',
            'subtitle' => 'Create a new business location'
        ]);
    }

    /**
     * Store a newly created shop in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Shop::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Create shop
            $shop = Shop::create([
                'name' => $validated['name'],
                'address' => $validated['address'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'owner_id' => Auth::id(),
            ]);
            
            // Add current user as owner
            ShopUser::create([
                'shop_id' => $shop->id,
                'user_id' => Auth::id(),
                'role' => 'owner',
            ]);
            
            DB::commit();
            
            // Set as current shop if first shop creation
            if (session('first_shop_creation')) {
                session(['current_shop_id' => $shop->id]);
                session(['current_shop_name' => $shop->name]);
                session(['current_shop_role' => 'owner']);
                session()->forget('first_shop_creation');
                
                return redirect()->route('dashboard')
                    ->with('success', 'Shop created successfully. You can now start managing your inventory.');
            }
            
            return redirect()->route('shops.index')
                ->with('success', 'Shop created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating shop: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified shop.
     */
    public function show(Shop $shop)
    {
        $this->authorize('view', $shop);
        
        // Load shop users with their roles
        $shopUsers = $shop->shopUsers()
            ->with('user')
            ->get();
            
        return view('shops.show', [
            'shop' => $shop,
            'shopUsers' => $shopUsers,
            'title' => 'Shop Details',
            'subtitle' => 'View shop information'
        ]);
    }

    /**
     * Show the form for editing the specified shop.
     */
    public function edit(Shop $shop)
    {
        $this->authorize('update', $shop);
        
        return view('shops.create', [
            'shop' => $shop,
            'title' => 'Edit Shop',
            'subtitle' => 'Modify shop information'
        ]);
    }

    /**
     * Update the specified shop in storage.
     */
    public function update(Request $request, Shop $shop)
    {
        $this->authorize('update', $shop);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        
        // Update shop
        $shop->update([
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
        ]);
        
        // Update session if this is the current shop
        if (session('current_shop_id') == $shop->id) {
            session(['current_shop_name' => $shop->name]);
        }
        
        return redirect()->route('shops.index')
            ->with('success', 'Shop updated successfully.');
    }

    /**
     * Remove the specified shop from storage.
     */
    public function destroy(Shop $shop)
    {
        $this->authorize('delete', $shop);
        
        // Check if shop has related records
        if ($shop->products()->count() > 0 || 
            $shop->stockIns()->count() > 0 || 
            $shop->sales()->count() > 0 || 
            $shop->dailyStockChecks()->count() > 0 || 
            $shop->financialEntries()->count() > 0) {
            
            return redirect()->route('shops.index')
                ->with('error', 'Cannot delete shop with related records.');
        }
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Delete shop users
            ShopUser::where('shop_id', $shop->id)->delete();
            
            // Delete shop
            $shop->delete();
            
            DB::commit();
            
            // Clear session if this was the current shop
            if (session('current_shop_id') == $shop->id) {
                session()->forget(['current_shop_id', 'current_shop_name', 'current_shop_role']);
            }
            
            return redirect()->route('shops.index')
                ->with('success', 'Shop deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error deleting shop: ' . $e->getMessage());
        }
    }
    
    /**
     * Show shop selection page.
     */
    public function select()
    {
        $user = Auth::user();
        $shops = $user->shops;
        
        if ($shops->count() == 1) {
            // If user has only one shop, set it as current and redirect to dashboard
            $shop = $shops->first();
            $shopUser = ShopUser::where('shop_id', $shop->id)
                ->where('user_id', $user->id)
                ->first();
                
            session(['current_shop_id' => $shop->id]);
            session(['current_shop_name' => $shop->name]);
            session(['current_shop_role' => $shopUser->role]);
            
            return redirect()->route('dashboard');
        }
        
        return view('shop.select', [
            'shops' => $shops,
            'title' => 'Select Shop',
            'subtitle' => 'Choose a shop to manage'
        ]);
    }
    
    /**
     * Set current shop in session.
     */
    public function set(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
        ]);
        
        $user = Auth::user();
        
        // Check if user has access to this shop
        $shopUser = ShopUser::where('shop_id', $validated['shop_id'])
            ->where('user_id', $user->id)
            ->first();
            
        if (!$shopUser) {
            return redirect()->route('shops.select')
                ->with('error', 'You do not have access to this shop.');
        }
        
        // Get shop name
        $shop = Shop::find($validated['shop_id']);
        
        // Set shop in session
        session(['current_shop_id' => $validated['shop_id']]);
        session(['current_shop_name' => $shop->name]);
        session(['current_shop_role' => $shopUser->role]);
        
        return redirect()->route('dashboard');
    }
}