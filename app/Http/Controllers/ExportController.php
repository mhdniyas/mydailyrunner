<?php

namespace App\Http\Controllers;

use App\Exports\StockReportExport;
use App\Exports\FinancialReportExport;
use App\Exports\CustomerDuesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export stock report to Excel.
     */
    public function stockReport(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $filters = [
            'product_id' => $request->product_id,
            'stock_level' => $request->stock_level,
        ];
        
        return Excel::download(new StockReportExport($shopId, $filters), 'stock_report.xlsx');
    }
    
    /**
     * Export financial report to Excel.
     */
    public function financialReport(Request $request)
    {
        $shopId = session('current_shop_id');
        
        $fromDate = $request->from_date ?? now()->startOfMonth()->format('Y-m-d');
        $toDate = $request->to_date ?? now()->format('Y-m-d');
        
        return Excel::download(new FinancialReportExport($shopId, $fromDate, $toDate), 'financial_report.xlsx');
    }
    
    /**
     * Export customer dues report to Excel.
     */
    public function customerDuesReport(Request $request)
    {
        $shopId = session('current_shop_id');
        
        return Excel::download(new CustomerDuesExport($shopId), 'customer_dues_report.xlsx');
    }
    
    /**
     * Export report to PDF.
     */
    public function exportPdf(Request $request, $type)
    {
        // Implementation for PDF export would go here
        // This would typically use a package like dompdf or barryvdh/laravel-dompdf
        
        return back()->with('error', 'PDF export not implemented yet.');
    }
}