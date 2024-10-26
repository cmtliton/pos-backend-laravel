<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stocks';

    protected $fillable = [
        'purchasedetail_id',
        'purchase_id',
        'PRN',
        'product_id',
        'quantity',
        'purchase_price',
        'mrp',
        'company_id',
    ];
}
