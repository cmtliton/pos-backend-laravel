<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'item_id',
        'brand_id',
        'name',
        'code',
        'purchase_price',
        'mrp',
        'quantity',
        'warranty',
        'measuring_unit',
        'status',
        'isPublished',
        'company_id',
        'user_id',
        'updator_id',
    ];
}
