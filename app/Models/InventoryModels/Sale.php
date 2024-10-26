<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'sales';

    protected $fillable = [
        'inv',
        'cart_total_quantity',
        'cart_total_amount',
        'discount',
        'shipping',
        'vat',
        'tax',
        'status',
        'buyer_code',
        'company_id',
        'user_id',
        'updator_id',
        'created_at',
        'updated_at'
    ];

public function receivepayment(): HasOne
    {
        return $this->hasOne(ReceivePayment::class, 'description_id', 'inv');
    }
}
