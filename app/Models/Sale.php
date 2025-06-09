<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_id',
        'customer_id',
        'sale_date',
        'total_amount',
        'paid_amount',
        'due_amount',
        'status',
        'notes',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sale_date' => 'date',
    ];

    /**
     * Get the shop that owns the sale.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the customer for the sale.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who created the sale.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the sale.
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Get the payments for the sale.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if the sale is fully paid.
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Check if the sale has a pending payment.
     */
    public function hasPendingPayment()
    {
        return $this->status === 'pending' || $this->status === 'advance';
    }

    /**
     * Get the payment status as a formatted string.
     */
    public function getPaymentStatusBadge()
    {
        switch ($this->status) {
            case 'paid':
                return '<span class="badge bg-success">Paid</span>';
            case 'advance':
                return '<span class="badge bg-warning">Partial</span>';
            case 'pending':
                return '<span class="badge bg-danger">Pending</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
}