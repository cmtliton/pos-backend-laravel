<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    use HasFactory;
    protected $table = 'payables';

    protected $fillable = [
        'description_id',
        'payee_code',
        'description',
        'amount',
        'company_id',
        'user_id',
        'updator_id',
    ];
}
