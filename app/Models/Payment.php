<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference',
        'notes',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_date' => 'date',
    ];

    /**
     * Get the sale that owns the payment.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the user who recorded the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment method as a formatted string.
     */
    public function getPaymentMethodBadge()
    {
        switch ($this->payment_method) {
            case 'cash':
                return '<span class="badge bg-success">Cash</span>';
            case 'bank':
                return '<span class="badge bg-primary">Bank</span>';
            case 'mobile':
                return '<span class="badge bg-info">Mobile</span>';
            default:
                return '<span class="badge bg-secondary">Other</span>';
        }
    }
}