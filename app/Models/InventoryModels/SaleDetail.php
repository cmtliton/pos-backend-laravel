<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $table = 'saledetails';

    protected $fillable = [
        'sale_id', 'sale_inv', 'product_id', 'quantity', 'purchase_price', 'mrp', 'vat_type', 'vat', 'tax_type', 'tax',
        'disc_type', 'discount', 'company_id'
    ];
}
