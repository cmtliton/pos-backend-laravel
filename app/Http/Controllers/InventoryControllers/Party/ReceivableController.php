<?php

namespace App\Http\Controllers\InventoryControllers\Party;

use App\Http\Controllers\Controller;
use App\Models\InventoryModels\Receivable;
//use Illuminate\Http\Request;

class ReceivableController extends Controller
{
   
    public static function CreateOrUpdateReceivable ($Receivables) {
        $description_id = $Receivables['description_id'] ? $Receivables['description_id'] : '';
        $isReceivable = Receivable::where('company_id', $Receivables['company_id'])->where('description_id', $description_id)->first();
        if($isReceivable) {
            //update
            $isReceivable->update([
                'buyer_code' => $Receivables['buyer_code'],
                'amount' => ($Receivables['cart_total_amount'] - $Receivables['collection']),
                'updator_id' => $Receivables['updator_id'],
               ]);
        } else {
            //insert
            Receivable::create([
                'description_id' => $Receivables['description_id'],
                'buyer_code' => $Receivables['buyer_code'],
                'description' => $Receivables['description'],
                'amount' => ($Receivables['cart_total_amount'] - $Receivables['collection']),
                'company_id' => $Receivables['company_id'],
                'user_id' => $Receivables['user_id'],
                'updator_id' => $Receivables['updator_id'],
            ]);
        }
    
}
public static function destroy($item) {
    $Receivable = Receivable::where('company_id', $item['company_id'])->where('description_id', $item['description_id']);
    $Receivable->delete();
    return response()->json(
        ['message' => 'Receivable Deleted!'],
        200
    );
}
}
