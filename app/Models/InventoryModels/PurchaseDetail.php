<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;


    protected $table = 'purchasedetails';

    protected $fillable = [
        'purchase_id',
        'PRN',
        'product_id',
        'purchase_price',
        'mrp',
        'quantity',
        'company_id',
    ];
}
