<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shop;
use App\Models\ShopUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserInvitation;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Only owner can manage users
        $this->authorize('viewAny', User::class);
        
        $users = User::withCount('shops')
            ->orderBy('name')
            ->paginate(10);
            
        return view('users.index', [
            'users' => $users,
            'title' => 'User Management',
            'subtitle' => 'Manage system users'
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        // Get shops owned by current user
        $shops = Shop::where('owner_id', Auth::id())
            ->orderBy('name')
            ->get();
            
        return view('users.create', [
            'shops' => $shops,
            'title' => 'Add User',
            'subtitle' => 'Create a new system user'
        ]);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'shops' => 'required|array',
            'shops.*' => 'exists:shops,id',
            'roles' => 'required|array',
            'roles.*' => 'in:owner,manager,finance,stock,viewer',
        ]);
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            
            // Assign shops and roles
            foreach ($validated['shops'] as $index => $shopId) {
                // Verify current user has owner access to this shop
                $shop = Shop::find($shopId);
                
                if ($shop->owner_id != Auth::id()) {
                    throw new \Exception('You do not have permission to add users to this shop.');
                }
                
                ShopUser::create([
                    'shop_id' => $shopId,
                    'user_id' => $user->id,
                    'role' => $validated['roles'][$index],
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('users.index')
                ->with('success', 'User created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        // Get user's shops with roles
        $shopUsers = $user->shopUsers()
            ->with('shop')
            ->get();
            
        return view('users.show', [
            'user' => $user,
            'shopUsers' => $shopUsers,
            'title' => 'User Details',
            'subtitle' => 'View user information'
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        return view('users.edit', [
            'user' => $user,
            'title' => 'Edit User',
            'subtitle' => 'Modify user information'
        ]);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Update user
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Cannot delete self
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Delete shop users
            ShopUser::where('user_id', $user->id)->delete();
            
            // Delete user
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form to manage user roles for shops.
     */
    public function roles(User $user)
    {
        $this->authorize('manageRoles', $user);
        
        // Get shops owned by current user
        $shops = Shop::where('owner_id', Auth::id())
            ->orderBy('name')
            ->get();
            
        // Get user's current shop roles
        $userShops = $user->shopUsers()
            ->pluck('role', 'shop_id')
            ->toArray();
            
        return view('users.roles', [
            'user' => $user,
            'shops' => $shops,
            'userShops' => $userShops,
            'title' => 'Manage User Roles',
            'subtitle' => 'Assign shops and roles to ' . $user->name
        ]);
    }
    
    /**
     * Update user roles for shops.
     */
    public function updateRoles(Request $request, User $user)
    {
        $this->authorize('manageRoles', $user);
        
        $validated = $request->validate([
            'shops' => 'required|array',
            'shops.*' => 'exists:shops,id',
            'roles' => 'required|array',
            'roles.*' => 'in:owner,manager,finance,stock,viewer',
        ]);
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Delete existing shop user records
            ShopUser::where('user_id', $user->id)->delete();
            
            // Create new shop user records
            foreach ($validated['shops'] as $index => $shopId) {
                // Verify current user has owner access to this shop
                $shop = Shop::find($shopId);
                
                if ($shop->owner_id != Auth::id()) {
                    throw new \Exception('You do not have permission to manage users for this shop.');
                }
                
                ShopUser::create([
                    'shop_id' => $shopId,
                    'user_id' => $user->id,
                    'role' => $validated['roles'][$index],
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('users.show', $user)
                ->with('success', 'User roles updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating user roles: ' . $e->getMessage());
        }
    }
    
    /**
     * Send invitation to user.
     */
    public function invite(Request $request, User $user)
    {
        $this->authorize('invite', $user);
        
        // Generate invitation token
        $token = Str::random(60);
        $user->invitation_token = $token;
        $user->save();
        
        // Send invitation email
        try {
            Mail::to($user->email)->send(new UserInvitation($user, $token));
            
            return redirect()->route('users.show', $user)
                ->with('success', 'Invitation sent successfully.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error sending invitation: ' . $e->getMessage());
        }
    }
}