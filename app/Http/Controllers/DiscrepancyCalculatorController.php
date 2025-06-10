<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscrepancyCalculatorController extends Controller
{
    /**
     * Display the discrepancy calculator page.
     */
    public function index()
    {
        $shopId = session('current_shop_id');
        
        // Get all products for the current shop
        $products = Product::where('shop_id', $shopId)
            ->orderBy('name')
            ->get();
            
        return view('discrepancy-calculator.index', [
            'products' => $products,
            'title' => 'Discrepancy Calculator',
            'subtitle' => 'Calculate inventory discrepancies with ease'
        ]);
    }

    /**
     * Calculate discrepancy via API
     */
    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'recorded_quantity' => 'required|numeric|min:0',
            'rows' => 'required|array|min:1',
            'rows.*.bags_per_row' => 'required|numeric|min:0',
            'rows.*.num_rows' => 'required|numeric|min:0',
        ]);

        $recordedQuantity = $validated['recorded_quantity'];
        $totalPhysical = 0;

        // Calculate total physical count
        foreach ($validated['rows'] as $row) {
            $totalPhysical += $row['bags_per_row'] * $row['num_rows'];
        }

        $discrepancy = $recordedQuantity - $totalPhysical;
        $discrepancyPercent = $recordedQuantity > 0 ? ($discrepancy / $recordedQuantity) * 100 : 0;

        $result = [
            'recorded_quantity' => $recordedQuantity,
            'total_physical' => $totalPhysical,
            'discrepancy' => $discrepancy,
            'discrepancy_percent' => round($discrepancyPercent, 2),
            'status' => $discrepancy == 0 ? 'match' : ($discrepancy > 0 ? 'over' : 'under'),
        ];

        // If product_id is provided, include product information
        if ($validated['product_id']) {
            $product = Product::find($validated['product_id']);
            if ($product && $product->shop_id == session('current_shop_id')) {
                $result['product'] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'unit' => $product->unit,
                    'current_stock' => $product->current_stock
                ];
            }
        }

        return response()->json($result);
    }
}
