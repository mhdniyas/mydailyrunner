<?php

namespace App\Http\Controllers;

use App\Models\FinancialEntry;
use App\Models\FinancialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialEntryController extends Controller
{
    /**
     * Display a listing of the financial entries.
     */
    public function index(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $query = FinancialEntry::where('shop_id', $shopId);
        
        // Filter by type if provided
        if ($request->has('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }
        
        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->where('financial_category_id', $request->category_id);
        }
        
        // Filter by date range if provided
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('entry_date', [$request->from_date, $request->to_date]);
        }
        
        $entries = $query->with('category')
            ->orderBy('entry_date', 'desc')
            ->paginate(15);
            
        // Get categories for filter
        $categories = FinancialCategory::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        // Calculate totals
        $totalIncome = FinancialEntry::where('shop_id', $shopId)
            ->where('type', 'income');
            
        $totalExpense = FinancialEntry::where('shop_id', $shopId)
            ->where('type', 'expense');
            
        // Apply date filters to totals if provided
        if ($request->has('from_date') && $request->has('to_date')) {
            $totalIncome->whereBetween('entry_date', [$request->from_date, $request->to_date]);
            $totalExpense->whereBetween('entry_date', [$request->from_date, $request->to_date]);
        }
        
        $totalIncome = $totalIncome->sum('amount');
        $totalExpense = $totalExpense->sum('amount');
        $netBalance = $totalIncome - $totalExpense;
        
        return view('financial-entries.index', [
            'entries' => $entries,
            'categories' => $categories,
            'selectedType' => $request->type ?? 'all',
            'selectedCategory' => $request->category_id ?? null,
            'from_date' => $request->from_date ?? null,
            'to_date' => $request->to_date ?? null,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netBalance' => $netBalance,
            'title' => 'Financial Entries',
            'subtitle' => 'Manage income and expenses'
        ]);
    }

    /**
     * Show the form for creating a new financial entry.
     */
    public function create()
    {
        $shopId = session('current_shop_id');
        
        // Get categories
        $categories = FinancialCategory::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        // Group categories by type
        $incomeCategories = $categories->where('type', 'income');
        $expenseCategories = $categories->where('type', 'expense');
        
        return view('financial-entries.create', [
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'title' => 'New Financial Entry',
            'subtitle' => 'Record income or expense'
        ]);
    }

    /**
     * Store a newly created financial entry in storage.
     */
    public function store(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'financial_category_id' => 'required|exists:financial_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:100',
        ]);
        
        // Verify category belongs to shop and matches type
        $category = FinancialCategory::findOrFail($validated['financial_category_id']);
        
        if ($category->shop_id != $shopId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid category selected.');
        }
        
        if ($category->type != $validated['type']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Category type does not match entry type.');
        }
        
        // Verify the shop belongs to the user
        $userShop = auth()->user()->shops()->where('id', $shopId)->first();
        if (!$userShop) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'You do not have access to this shop.');
        }
        
        // Create financial entry
        FinancialEntry::create([
            'shop_id' => $shopId,
            'type' => $validated['type'],
            'financial_category_id' => $validated['financial_category_id'],
            'amount' => $validated['amount'],
            'entry_date' => $validated['entry_date'],
            'description' => $validated['description'] ?? null,
            'reference' => $validated['reference'] ?? null,
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->route('financial-entries.index')
            ->with('success', ucfirst($validated['type']) . ' entry created successfully.');
    }

    /**
     * Display the specified financial entry.
     */
    public function show(FinancialEntry $financialEntry)
    {
        $this->authorize('view', $financialEntry);
        
        $financialEntry->load(['category', 'user']);
        
        return view('financial-entries.show', [
            'entry' => $financialEntry,
            'title' => ucfirst($financialEntry->type) . ' Details',
            'subtitle' => 'View financial entry information'
        ]);
    }

    /**
     * Show the form for editing the specified financial entry.
     */
    public function edit(FinancialEntry $financialEntry)
    {
        $this->authorize('update', $financialEntry);
        
        $shopId = session('current_shop_id');
        
        // Get categories
        $categories = FinancialCategory::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        // Group categories by type
        $incomeCategories = $categories->where('type', 'income');
        $expenseCategories = $categories->where('type', 'expense');
        
        return view('financial-entries.edit', [
            'entry' => $financialEntry,
            'incomeCategories' => $incomeCategories,
            'expenseCategories' => $expenseCategories,
            'title' => 'Edit ' . ucfirst($financialEntry->type),
            'subtitle' => 'Modify financial entry'
        ]);
    }

    /**
     * Update the specified financial entry in storage.
     */
    public function update(Request $request, FinancialEntry $financialEntry)
    {
        $this->authorize('update', $financialEntry);
        
        $shopId = session('current_shop_id');
        
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'financial_category_id' => 'required|exists:financial_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'entry_date' => 'required|date',
            'description' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:100',
        ]);
        
        // Verify category belongs to shop and matches type
        $category = FinancialCategory::findOrFail($validated['financial_category_id']);
        
        if ($category->shop_id != $shopId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid category selected.');
        }
        
        if ($category->type != $validated['type']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Category type does not match entry type.');
        }
        
        // Update financial entry
        $financialEntry->update([
            'type' => $validated['type'],
            'financial_category_id' => $validated['financial_category_id'],
            'amount' => $validated['amount'],
            'entry_date' => $validated['entry_date'],
            'description' => $validated['description'] ?? null,
            'reference' => $validated['reference'] ?? null,
        ]);
        
        return redirect()->route('financial-entries.index')
            ->with('success', ucfirst($validated['type']) . ' entry updated successfully.');
    }

    /**
     * Remove the specified financial entry from storage.
     */
    public function destroy(FinancialEntry $financialEntry)
    {
        $this->authorize('delete', $financialEntry);
        
        $type = $financialEntry->type;
        $financialEntry->delete();
        
        return redirect()->route('financial-entries.index')
            ->with('success', ucfirst($type) . ' entry deleted successfully.');
    }
}