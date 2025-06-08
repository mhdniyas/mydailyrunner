<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShopFinanceEntry;
use App\Models\PersonalFinanceEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'total_products' => Product::count(),
            'low_stock_count' => Product::whereRaw('(SELECT COALESCE(SUM(CASE WHEN entry_type = "in" THEN quantity ELSE -quantity END), 0) FROM stock_entries WHERE product_id = products.id) < threshold')->count(),
            'today_income' => ShopFinanceEntry::whereDate('entry_date', Carbon::today())
                ->where('type', 'income')
                ->sum('amount'),
            'today_expenses' => ShopFinanceEntry::whereDate('entry_date', Carbon::today())
                ->where('type', 'expense')
                ->sum('amount'),
        ];

        // Get low stock products
        $lowStockProducts = Product::select('products.*')
            ->selectRaw('(SELECT COALESCE(SUM(CASE WHEN entry_type = "in" THEN quantity ELSE -quantity END), 0) FROM stock_entries WHERE product_id = products.id) as current_stock')
            ->havingRaw('current_stock < threshold')
            ->orderBy('current_stock')
            ->limit(5)
            ->get();

        // Prepare chart data
        $chartData = $this->getFinanceChartData();

        return view('dashboard', compact('stats', 'lowStockProducts', 'chartData'));
    }

    private function getFinanceChartData()
    {
        $dates = collect();
        $income = collect();
        $expenses = collect();

        // Get last 7 days data
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates->push($date->format('M d'));

            $dayIncome = ShopFinanceEntry::whereDate('entry_date', $date)
                ->where('type', 'income')
                ->sum('amount');
            $income->push($dayIncome);

            $dayExpenses = ShopFinanceEntry::whereDate('entry_date', $date)
                ->where('type', 'expense')
                ->sum('amount');
            $expenses->push($dayExpenses);
        }

        return [
            'labels' => $dates,
            'income' => $income,
            'expenses' => $expenses,
        ];
    }
}
