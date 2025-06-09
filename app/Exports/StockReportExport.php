<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $shopId;
    protected $filters;

    public function __construct($shopId, $filters = [])
    {
        $this->shopId = $shopId;
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Product::where('shop_id', $this->shopId);
        
        // Apply filters if provided
        if (isset($this->filters['product_id'])) {
            $query->where('id', $this->filters['product_id']);
        }
        
        if (isset($this->filters['stock_level'])) {
            if ($this->filters['stock_level'] === 'low') {
                $query->where('current_stock', '>', 0)
                    ->where('current_stock', '<=', \DB::raw('min_stock_level'));
            } elseif ($this->filters['stock_level'] === 'out') {
                $query->where('current_stock', '<=', 0);
            } elseif ($this->filters['stock_level'] === 'normal') {
                $query->where('current_stock', '>', \DB::raw('min_stock_level'));
            }
        }
        
        return $query->orderBy('name')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Product',
            'Current Stock',
            'Unit',
            'Min Level',
            'Avg Cost',
            'Total Value',
        ];
    }

    /**
     * @param mixed $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->name,
            $product->current_stock,
            $product->unit,
            $product->min_stock_level,
            $product->avg_cost,
            $product->current_stock * $product->avg_cost,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Stock Report';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}