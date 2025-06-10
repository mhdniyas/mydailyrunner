<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerPaymentController extends Controller
{
    /**
     * Display a listing of customer payments.
     */
    public function index(Request $request)
    {
        $shopId = session('current_shop_id');
        
        // Get sales with pending payments
        $query = Sale::where('shop_id', $shopId)
            ->where('status', '!=', 'paid')
            ->with(['customer', 'payments']);
            
        // Filter by customer if provided
        if ($request->has('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        
        // Filter by date range if provided
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('sale_date', [$request->from_date, $request->to_date]);
        }
        
        $pendingSales = $query->orderBy('sale_date', 'desc')
            ->paginate(10);
            
        // Get customers for filter
        $customers = Customer::where('shop_id', $shopId)
            ->where('is_walk_in', false)
            ->orderBy('name')
            ->get();
            
        return view('customer-payments.index', [
            'pendingSales' => $pendingSales,
            'customers' => $customers,
            'selectedCustomer' => $request->customer_id ?? null,
            'from_date' => $request->from_date ?? null,
            'to_date' => $request->to_date ?? null,
            'title' => 'Customer Payments',
            'subtitle' => 'Manage pending payments and dues'
        ]);
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Sale $sale)
    {
        $this->authorize('view', $sale);
        
        // Check if sale is already paid
        if ($sale->status === 'paid') {
            return redirect()->route('customer-payments.index')
                ->with('error', 'This sale is already fully paid.');
        }
        
        $sale->load(['customer', 'items.product', 'payments']);
        
        return view('customer-payments.create', [
            'sale' => $sale,
            'title' => 'Record Payment',
            'subtitle' => 'Add payment for sale #' . $sale->id
        ]);
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|in:cash,bank,mobile',
            'reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Get the sale
        $sale = Sale::findOrFail($validated['sale_id']);
        $this->authorize('update', $sale);
        
        // Check if payment amount is valid
        if ($validated['amount'] > $sale->due_amount) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Payment amount cannot exceed the due amount.');
        }
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            // Verify the shop belongs to the user
            $shopId = $sale->shop_id;
            $userShop = auth()->user()->shops()->where('id', $shopId)->first();
            if (!$userShop) {
                throw new \Exception('You do not have access to this shop.');
            }
            
            // Create payment
            $payment = Payment::create([
                'sale_id' => $sale->id,
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'user_id' => Auth::id(),
            ]);
            
            // Update sale
            $newPaidAmount = $sale->paid_amount + $validated['amount'];
            $newDueAmount = $sale->total_amount - $newPaidAmount;
            
            // Determine new status
            if ($newDueAmount <= 0) {
                $newStatus = 'paid';
            } else {
                $newStatus = 'advance';
            }
            
            $sale->update([
                'paid_amount' => $newPaidAmount,
                'due_amount' => $newDueAmount,
                'status' => $newStatus,
            ]);
            
            DB::commit();
            
            return redirect()->route('customer-payments.index')
                ->with('success', 'Payment recorded successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error recording payment: ' . $e->getMessage());
        }
    }
}