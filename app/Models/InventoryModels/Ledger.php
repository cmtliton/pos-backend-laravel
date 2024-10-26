<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'TRN',
        'type',
        'account_id',
        'description',
        'amount',
        'party_code',
        'company_id',
        'user_id',
        'updator_id'
    ];

    public function receivepayment() : HasOne {
        return $this->hasOne(ReceivePayment::class,'description_id', 'TRN');
    }
}
