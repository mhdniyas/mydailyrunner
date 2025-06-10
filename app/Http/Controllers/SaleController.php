<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the sales.
     */
    public function index(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $query = Sale::where('shop_id', $shopId)
            ->with(['customer', 'items.product']);
            
        // Filter by status if provided
        if ($request->has('status') && in_array($request->status, ['paid', 'pending', 'advance'])) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range if provided
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('sale_date', [$request->from_date, $request->to_date]);
        }
        
        $sales = $query->orderBy('sale_date', 'desc')
            ->paginate(10);
            
        return view('sales.index', [
            'sales' => $sales,
            'status' => $request->status ?? 'all',
            'from_date' => $request->from_date ?? null,
            'to_date' => $request->to_date ?? null,
            'title' => 'Sales',
            'subtitle' => 'Manage your sales transactions'
        ]);
    }

    /**
     * Show the form for creating a new sale.
     */
    public function create()
    {
        $shopId = session('current_shop_id');
        $products = Product::where('shop_id', $shopId)
            ->where('current_stock', '>', 0)
            ->orderBy('name')
            ->get();
            
        // Get or create walk-in customer
        $walkInCustomer = Customer::firstOrCreate(
            ['shop_id' => $shopId, 'is_walk_in' => true],
            ['name' => 'Walk-in Customer', 'phone' => null, 'address' => null]
        );
        
        // Get regular customers
        $customers = Customer::where('shop_id', $shopId)
            ->where('is_walk_in', false)
            ->orderBy('name')
            ->get();
            
        // Add walk-in customer to the beginning
        $customers->prepend($walkInCustomer);
        
        return view('sales.create', [
            'products' => $products,
            'customers' => $customers,
            'title' => 'New Sale',
            'subtitle' => 'Create a new sales transaction'
        ]);
    }

    /**
     * Store a newly created sale in storage.
     */
    public function store(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0.1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Determine sale status
        $totalAmount = $validated['total_amount'];
        $paidAmount = $validated['paid_amount'];
        
        if ($paidAmount == 0) {
            $status = 'pending';
        } elseif ($paidAmount < $totalAmount) {
            $status = 'advance';
        } else {
            $status = 'paid';
        }
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Verify the shop belongs to the user
            $userShop = auth()->user()->shops()->where('id', $shopId)->first();
            if (!$userShop) {
                throw new \Exception('You do not have access to this shop.');
            }
            
            // Create sale
            $sale = Sale::create([
                'shop_id' => $shopId,
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $totalAmount - $paidAmount,
                'status' => $status,
                'notes' => $validated['notes'] ?? null,
                'user_id' => Auth::id(),
            ]);
            
            // Create sale items and update stock
            $productIds = $request->input('product_id');
            $quantities = $request->input('quantity');
            $prices = $request->input('price');
            
            foreach ($productIds as $index => $productId) {
                $quantity = $quantities[$index];
                $price = $prices[$index];
                $subtotal = $quantity * $price;
                
                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'user_id' => Auth::id(),
                ]);
                
                // Update product stock
                $product = Product::find($productId);
                
                // Check if enough stock
                if ($product->current_stock < $quantity) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }
                
                $product->current_stock -= $quantity;
                $product->save();
            }
            
            DB::commit();
            
            return redirect()->route('sales.index')
                ->with('success', 'Sale created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating sale: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified sale.
     */
    public function show(Sale $sale)
    {
        $this->authorize('view', $sale);
        
        $sale->load(['customer', 'items.product', 'user', 'payments']);
        
        return view('sales.show', [
            'sale' => $sale,
            'title' => 'Sale Details',
            'subtitle' => 'View sale transaction information'
        ]);
    }

    /**
     * Show the form for editing the specified sale.
     */
    public function edit(Sale $sale)
    {
        $this->authorize('update', $sale);
        
        $shopId = session('current_shop_id');
        
        // Only allow editing if no payments have been made after initial creation
        if ($sale->payments->count() > 1) {
            return redirect()->route('sales.show', $sale)
                ->with('error', 'Cannot edit sale with multiple payments. Please create a new sale instead.');
        }
        
        $sale->load(['customer', 'items.product']);
        
        $products = Product::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        $customers = Customer::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        return view('sales.edit', [
            'sale' => $sale,
            'products' => $products,
            'customers' => $customers,
            'title' => 'Edit Sale',
            'subtitle' => 'Modify sale transaction'
        ]);
    }

    /**
     * Update the specified sale in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $this->authorize('update', $sale);
        
        // Only allow editing if no payments have been made after initial creation
        if ($sale->payments->count() > 1) {
            return redirect()->route('sales.show', $sale)
                ->with('error', 'Cannot edit sale with multiple payments. Please create a new sale instead.');
        }
        
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:0.1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Determine sale status
        $totalAmount = $validated['total_amount'];
        $paidAmount = $validated['paid_amount'];
        
        if ($paidAmount == 0) {
            $status = 'pending';
        } elseif ($paidAmount < $totalAmount) {
            $status = 'advance';
        } else {
            $status = 'paid';
        }
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // First, restore stock for all items
            foreach ($sale->items as $item) {
                $product = $item->product;
                $product->current_stock += $item->quantity;
                $product->save();
            }
            
            // Delete old items
            SaleItem::where('sale_id', $sale->id)->delete();
            
            // Update sale
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'sale_date' => $validated['sale_date'],
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $totalAmount - $paidAmount,
                'status' => $status,
                'notes' => $validated['notes'] ?? null,
            ]);
            
            // Create new sale items and update stock
            $productIds = $request->input('product_id');
            $quantities = $request->input('quantity');
            $prices = $request->input('price');
            
            foreach ($productIds as $index => $productId) {
                $quantity = $quantities[$index];
                $price = $prices[$index];
                $subtotal = $quantity * $price;
                
                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);
                
                // Update product stock
                $product = Product::find($productId);
                
                // Check if enough stock
                if ($product->current_stock < $quantity) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }
                
                $product->current_stock -= $quantity;
                $product->save();
            }
            
            // Update initial payment record if it exists
            if ($sale->payments->count() == 1) {
                $payment = $sale->payments->first();
                $payment->amount = $paidAmount;
                $payment->save();
            }
            
            DB::commit();
            
            return redirect()->route('sales.show', $sale)
                ->with('success', 'Sale updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating sale: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified sale from storage.
     */
    public function destroy(Sale $sale)
    {
        $this->authorize('delete', $sale);
        
        // Only allow deletion if no payments have been made after initial creation
        if ($sale->payments->count() > 1) {
            return redirect()->route('sales.show', $sale)
                ->with('error', 'Cannot delete sale with multiple payments.');
        }
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Restore stock for all items
            foreach ($sale->items as $item) {
                $product = $item->product;
                $product->current_stock += $item->quantity;
                $product->save();
            }
            
            // Delete sale items
            SaleItem::where('sale_id', $sale->id)->delete();
            
            // Delete payments
            $sale->payments()->delete();
            
            // Delete sale
            $sale->delete();
            
            DB::commit();
            
            return redirect()->route('sales.index')
                ->with('success', 'Sale deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error deleting sale: ' . $e->getMessage());
        }
    }
}