<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    use HasFactory;
    protected $table = 'receivables';

    protected $fillable = [
        'description_id',
        'buyer_code',
        'description',
        'amount',
        'company_id',
        'user_id',
        'updator_id',
    ];
}
