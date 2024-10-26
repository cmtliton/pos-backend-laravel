<?php

namespace App\Http\Controllers\InventoryControllers\Party;

use App\Http\Controllers\Controller;
use App\Models\InventoryModels\Payable;

//use Illuminate\Http\Request;

class PayableController extends Controller
{
    public static function CreateOrUpdatePayable ($payables) {
            $description_id = $payables['description_id'] ? $payables['description_id'] : '';
            $isPayable = Payable::where('company_id', $payables['company_id'])->where('description_id', $description_id)->first();
            if($isPayable) {
                //update
                $isPayable->update([
                    //'payee_id' => $payables['payee_id'],
                    'payee_code' => $payables['payee_code'],
                    'amount' => ($payables['cart_total_amount'] - $payables['purchase_payment']),
                    'updator_id' => $payables['updator_id'],
                   ]);
            } else {
                //insert
                Payable::create([
                    'description_id' => $payables['description_id'],
                    //'payee_id' => $payables['payee_id'],
                    'payee_code' => $payables['payee_code'],
                    'description' => $payables['description'],
                    'amount' => ($payables['cart_total_amount'] - $payables['purchase_payment']),
                    'company_id' => $payables['company_id'],
                    'user_id' => $payables['user_id'],
                    'updator_id' => $payables['updator_id'],
                ]);
            }
        
    }
    public static function destroy($item) {
        $Payable = Payable::where('company_id', $item['company_id'])->where('description_id', $item['description_id']);
        $Payable->delete();
        return response()->json(
            ['message' => 'Payable Deleted!'],
            200
        );
    }
}
