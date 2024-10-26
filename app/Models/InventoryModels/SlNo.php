<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlNo extends Model
{
    use HasFactory;

    protected $table = 'slnos';
    public $timestamps = false;

    protected $fillable = [
        'purchasedetail_id',
        'product_id',
        'slno',
        'status',
        'company_id',
    ];
}
