<?php

namespace App\Http\Controllers\InventoryControllers\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Controllers\InventoryControllers\Purchases\PurchaseDetailController;
use App\Models\InventoryModels\Purchase;
//use App\Models\InventoryModels\PurchaseDetail;
use App\Http\Requests\InventoryRequests\Purchase\PurchaseRequest;
use App\Http\Controllers\InventoryControllers\Party\ReceivePaymentController;
use App\Http\Controllers\InventoryControllers\Party\PayableController;
use App\Http\Controllers\InventoryControllers\Product\StockController;

//use App\Models\InventoryModels\ReceivePayment;

class PurchaseController extends Controller
{
    public static function initializeId($cmpid) {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $purchaseId = Purchase::orderBy('PRN', 'DESC')->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
        ->where('company_id', $cmpid)->max('PRN');
        if(empty($purchaseId))
        {
            $number = 'PRN'.date('y').date('m').'00001';
            return $number;
        } else {
            $prefix = substr($purchaseId, 0, 7);
            $idd = str_replace($prefix,'', $purchaseId);
            $id = str_pad($idd+1,5, 0, STR_PAD_LEFT);
            $number = 'PRN'.date('y').date('m').$id;
            return $number;
        }
       
    }

    public function getPurchasesByCmpId($id) {
        $Purchases = Purchase::with('receivepayment:description_id,amount as purchase_payment')->where('company_id', $id)->latest()->get();
        return $Purchases;
    }

    public static function store(PurchaseRequest $request) {
        $Purchase = Purchase::create([
            'PRN' => $request->PRN,
            'cart_total_quantity' => $request->cart_total_quantity,
            'cart_total_amount' => $request->cart_total_amount,
            'status' => $request->status === true ? 1:0,
            'supplier_code' => $request->supplier_code,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
            'updator_id' => $request->user_id,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at
        ]);
        $items = [];
        foreach($request->purchases as $key => $product) {
           $items[$key] = PurchaseDetailController::store($Purchase, $product);
        }

        /** **********Payment Information & Stock Declaration********** */
        $purchase_payment = self::PaymentStockInforamtion($Purchase, $request);
       
        return response()->json([
            'Purchase' => $Purchase,
            'purchase_payment' => $purchase_payment,
            'PurchaseItems' => $items,
            'message' => 'Purchase Saved!'
        ],200);

        
    }

    public static function update(PurchaseRequest $request, string $id)
    {
        if(count($request->itemsForDel) > 0) {
            PurchaseDetailController::PurchasedDeleteByArray($request->itemsForDel);
            //if($request->status === true) {
                StockController::StockDeleteByArrayIds($request->itemsForDel);
            //}
        }

        $Purchase = Purchase::findOrFail($id)->update([
            'cart_total_quantity' => $request->cart_total_quantity,
            'cart_total_amount' => $request->cart_total_amount,
            'status' => $request->status === true ? 1:0,
            'supplier_code' => $request->supplier_code,
            'updator_id' => $request->updator_id,
            'updated_at' => $request->updated_at,
        ]);

        $purchaseItems = [];
        foreach($request->purchases as $key => $product) {
            $purchaseItems[$key] = PurchaseDetailController::update($product);
        }

          /** **********Payment Information & Stock Declaration********** */
       self::PaymentStockInforamtion($id, $request);

        if($request->status === true) {
            //Stock update
            StockController::CreateOrUpdateStock($purchaseItems);
        } 

        if(count($request->slnos)>0) {
            //SlNos update
            $slnos = [];
            foreach($request->slnos as $key => $slno) {
                $slnos[$key] = SlNoController::CreateOrUpdateSlNo($slno);
            }
        } 

       return response()->json(
        [
            'Purchase' => $Purchase,
            'PurchaseItems' => $purchaseItems,
            'message' => 'Purchase Updated!',
        ],
        200
       );
    }

    public static function PaymentStockInforamtion($purchase_id, PurchaseRequest $request) {
        
        $payables = [];
            $payables['description_id'] = $request->PRN;
            $payables['payee_code'] = $request->supplier_code;
            $payables['description'] = 'Purchase';
            $payables['type'] = 'Payment';
            $payables['cart_total_amount'] = $request->cart_total_amount;
            $payables['company_id'] = $request->company_id;
            $payables['user_id'] = $request->user_id;
            $payables['updator_id'] = $request->updator_id;
            $payables['purchase_payment'] = $request->purchase_payment;

            $request->cart_total_amount !== $request->purchase_payment ? PayableController::CreateOrUpdatePayable($payables): PayableController::destroy($payables);
            $purchase_payment = ReceivePaymentController::CreateOrUpdateReceivePayment($payables);
            return $purchase_payment;
        
    }

    public function destroy(int $id) {
        $Purchase = Purchase::findOrFail($id);
        $item = [];
        $item['description_id'] = $Purchase->PRN;
        $item['company_id'] = $Purchase->company_id;
        PayableController::destroy($item);
        ReceivePaymentController::destroy($item);
        $Purchase->delete();
        return response()->json(
            ['message' => 'Purchase Deleted!'],
            200
        );
    }
}
