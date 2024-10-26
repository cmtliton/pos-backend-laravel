<?php

namespace App\Http\Controllers\InventoryControllers\Party;

use App\Models\InventoryModels\ReceivePayment;
use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;

class ReceivePaymentController extends Controller
{
    public static function CreateOrUpdateReceivePayment ($receivepayments) {
            $description_id = $receivepayments['description_id'] ? $receivepayments['description_id'] : '';
            $isReceivePayment = ReceivePayment::where('company_id', $receivepayments['company_id'])->where('description_id', $description_id)->first();
            
            if ($isReceivePayment) {
                //update
                $isReceivePayment->update([
                    'description' => $receivepayments['description'],
                    'type' => $receivepayments['type'],
                    'amount' => $receivepayments['purchase_payment'],
                    'updator_id' => $receivepayments['updator_id'],
                   ]);

            } else {
                //insert
                $purchase_payment = ReceivePayment::create([
                    'description_id' => $receivepayments['description_id'],
                    'description' => $receivepayments['description'],
                    'type' => $receivepayments['type'],
                    'amount' => $receivepayments['purchase_payment'],
                    'company_id' => $receivepayments['company_id'],
                    'user_id' => $receivepayments['user_id'],
                    'updator_id' => $receivepayments['updator_id'],
                ]);
                return $purchase_payment->amount;
            }
    }

    public static function CreateOrUpdateReceivePaymentByReceived($receivepayments) {
        $description_id = $receivepayments['description_id'] ? $receivepayments['description_id'] : '';
        $isReceivePayment = ReceivePayment::where('company_id', $receivepayments['company_id'])->where('description_id', $description_id)->first();
        
        if ($isReceivePayment) {
            //update
            $isReceivePayment->update([
                'description' => $receivepayments['description'],
                'type' => $receivepayments['type'],
                'amount' => $receivepayments['purchase_payment'],
                'updator_id' => $receivepayments['updator_id'],
               ]);

        } else {
            //insert
            $purchase_payment = ReceivePayment::create([
                'description_id' => $receivepayments['description_id'],
                'description' => $receivepayments['description'],
                'type' => $receivepayments['type'],
                'amount' => $receivepayments['purchase_payment'],
                'company_id' => $receivepayments['company_id'],
                'user_id' => $receivepayments['user_id'],
                'updator_id' => $receivepayments['updator_id'],
            ]);
            return $purchase_payment->amount;
        }
}

    public static function destroy($item) {
        $ReceivePayment = ReceivePayment::where('company_id', $item['company_id'] )->where('description_id',$item['description_id']);
        $ReceivePayment->delete();
        return response()->json(
            ['message' => 'ReceivePayment Deleted!'],
            200
        );
    }
}
