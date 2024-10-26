<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'account_type_id',
        'status',
        'company_id',
        'user_id',
        'updator_id'
    ];
}
