<?php

namespace App\Models\InventoryModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceivePayment extends Model
{
    use HasFactory;

    protected $table = 'receivespayments';

    protected $fillable = [
        'description_id',
        'description',
        'type',
        'amount',
        'company_id',
        'user_id',
        'updator_id',
    ];

    public function purchase() : BelongsTo {
        return $this->belongsTo(Purchase::class,'description_id', 'PRN');
    }

    public function ledger(): BelongsTo
    {
    return $this->belongsTo(Ledger::class, 'description_id', 'TRN');
    }
}
