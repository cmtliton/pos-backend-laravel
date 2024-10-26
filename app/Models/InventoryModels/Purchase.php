<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'purchases';

    protected $fillable = [
        'PRN',
        'cart_total_quantity',
        'cart_total_amount',
        'status',
        'supplier_code',
        'company_id',
        'user_id',
        'updator_id',
        'created_at',
        'updated_at'
    ];
    
public function receivepayment(): HasOne
{
    return $this->hasOne(ReceivePayment::class, 'description_id', 'PRN');
}


}
