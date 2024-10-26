<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'mobileno',
        'addr',
        'type',
        'status',
        'company_id',
        'user_id',
        'updator_id'
    ];
}
