<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerDuesExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $shopId;

    public function __construct($shopId)
    {
        $this->shopId = $shopId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customer::where('shop_id', $this->shopId)
            ->where('is_walk_in', false)
            ->whereHas('sales', function ($q) {
                $q->where('status', '!=', 'paid');
            })
            ->withCount(['sales' => function ($q) {
                $q->where('status', '!=', 'paid');
            }])
            ->withSum(['sales as total_due' => function ($q) {
                $q->where('status', '!=', 'paid');
            }], 'due_amount')
            ->orderByDesc('total_due')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Customer',
            'Phone',
            'Address',
            'Pending Sales',
            'Total Due Amount',
        ];
    }

    /**
     * @param mixed $customer
     * @return array
     */
    public function map($customer): array
    {
        return [
            $customer->name,
            $customer->phone,
            $customer->address,
            $customer->sales_count,
            $customer->total_due,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Customer Dues';
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