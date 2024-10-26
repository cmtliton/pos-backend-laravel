<?php

namespace App\Http\Controllers\InventoryControllers\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InventoryControllers\Party\PayableController;
use App\Http\Controllers\InventoryControllers\Party\ReceivableController;
use Illuminate\Http\Request;
use App\Models\InventoryModels\Ledger;
use App\Http\Controllers\InventoryControllers\Party\ReceivePaymentController;

class LedgerController extends Controller
{
    public static function initializeId($cmpid) {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $LedgerId = Ledger::orderBy('TRN', 'DESC')->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
        ->where('company_id', $cmpid)->max('TRN');
        if(empty($LedgerId))
        {
            $number = 'TRN'.date('y').date('m').'00001';
            return $number;
        } else {
            $prefix = substr($LedgerId, 0, 7);
            $idd = str_replace($prefix,'', $LedgerId);
            $id = str_pad($idd+1,5, 0, STR_PAD_LEFT);
            $number = 'TRN'.date('y').date('m').$id;
            return $number;
        }
       
    }

    public function getLedgersByCmpId($id) {
        $Ledgers = Ledger::with('receivepayment:description_id,amount as payment')->where('company_id', $id)->latest()->get();
        return $Ledgers;
    }

    public static function store(Request $request) {
        $Ledger = Ledger::create([
            'TRN' => $request->TRN,
            'type' => $request->type,
            'account_id' => $request->account_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'party_code' => $request->party_code,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
            'updator_id' => $request->user_id,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at
        ]);

       self::processForTrans($request);

        return response()->json([
            'Ledger' => $Ledger,
            'message' => 'Ledger Saved!'
        ],200);

        
    }

    public static function update(Request $request, string $id)
    {

        $Ledger = Ledger::findOrFail($id)->update([
            'type' => $request->type,
            'account_id' => $request->account_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'party_code' => $request->party_code,
            'updator_id' => $request->updator_id,
        ]);

        self::processForTrans($request);

       return response()->json(
        [
            'Ledger' => $Ledger,
            'message' => 'Ledger Updated!',
        ],
        200
       );
    }

    public static function processForTrans($request) {
        $receivepayments = [];
        $receivepayments['description_id'] = $request->TRN;
        $receivepayments['description'] = $request->description;
        $receivepayments['cart_total_amount'] = $request->amount;
        $receivepayments['purchase_payment'] = $request->payment;
        $receivepayments['collection'] = $request->payment;
        $receivepayments['party_code'] = $request->party_code; /** party_code = Buyer Code/Supplier Code */
        $receivepayments['buyer_code'] = $request->party_code;
        $receivepayments['payee_code'] = $request->party_code;
        $receivepayments['company_id'] = $request->company_id;
        $receivepayments['user_id'] = $request->user_id;
        $receivepayments['updator_id'] = $request->updator_id;
        $receivepayments['type_code'] = $request->type_code;
        $receivepayments['type'] = $request->type;
        self::transaction($receivepayments);
    }

    public static function transaction($receivepayments) {
                /** **********Payment Information ********** */
                switch($receivepayments['type']) {
                    case "Payment": 
                        ReceivePaymentController::CreateOrUpdateReceivePayment($receivepayments);
                        $receivepayments['cart_total_amount'] !== $receivepayments['purchase_payment'] ? PayableController::CreateOrUpdatePayable($receivepayments) : PayableController::destroy($receivepayments);
                        break;
                    case "Receive": 
                        ReceivePaymentController::CreateOrUpdateReceivePaymentByReceived($receivepayments);
                        $receivepayments['cart_total_amount'] !== $receivepayments['collection'] ? ReceivableController::CreateOrUpdateReceivable($receivepayments) : ReceivableController::destroy($receivepayments);
                        break;
                    default:
                        ReceivePaymentController::CreateOrUpdateReceivePayment($receivepayments);
                        $receivepayments['cart_total_amount'] !== $receivepayments['purchase_payment'] ? PayableController::CreateOrUpdatePayable($receivepayments) : PayableController::destroy($receivepayments);
                        
                    }
    }

    public function destroy(int $id) {
        $Ledger = Ledger::findOrFail($id);
        $item = [];
        $item['description_id'] = $Ledger->TRN;
        $item['company_id'] = $Ledger->company_id;
        PayableController::destroy($item);
       ReceivePaymentController::destroy($item);
        $Ledger->delete();
        return response()->json(
            ['message' => 'Ledger Deleted!'],
            200
        );
    }
}
