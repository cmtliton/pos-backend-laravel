<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    
    protected $table = 'product_images';

    protected $fillable = [
        'image',
        'product_id',
        'company_id',
        'user_id',
        'updator_id',
    ];
}
