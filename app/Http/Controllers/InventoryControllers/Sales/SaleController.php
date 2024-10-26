<?php

namespace App\Http\Controllers\InventoryControllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\InventoryModels\Sale;
use App\Http\Requests\InventoryRequests\Sale\SaleRequest;
use App\Http\Controllers\InventoryControllers\Product\StockController;
use App\Http\Controllers\InventoryControllers\Party\ReceivableController;
use App\Http\Controllers\InventoryControllers\Party\ReceivePaymentController;
use App\Http\Controllers\InventoryControllers\Purchases\SlNoController;

class SaleController extends Controller
{
    public static function initializeId($cmpid) {
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $saleId = Sale::orderBy('inv', 'DESC')->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59'])
        ->where('company_id', $cmpid)->max('inv');
        if(empty($saleId))
        {
            $number = 'INV'.date('y').date('m').'00001';
            return $number;
        } else {
            $prefix = substr($saleId, 0, 7);
            $idd = str_replace($prefix,'', $saleId);
            $id = str_pad($idd+1,5, 0, STR_PAD_LEFT);
            $number = 'INV'.date('y').date('m').$id;
            return $number;
        }
       
    }

    public function getSalesByCmpId($id) {
        $Sales = Sale::with('receivepayment:description_id,amount as collection')->where('company_id', $id)->latest()->get();
        return $Sales;
    }

    public static function store(SaleRequest $request) {
        $Sale = Sale::create([
            'inv' => $request->inv,
            'cart_total_quantity' => $request->cart_total_quantity,
            'cart_total_amount' => $request->cart_total_amount,
            'discount' => $request->discount,
            'shipping' => $request->shipping,
            'vat' => $request->vat,
            'tax' => $request->tax,
            'status' => $request->status === true ? 1:0,
            'buyer_code' => $request->buyer_code,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
            'updator_id' => $request->user_id,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at
        ]);
        $items = [];
        foreach($request->Sales as $key => $product) {
           $items[$key] = SaleDetailController::store($Sale, $product);
        }

        /** **********Payment Information & Stock Declaration********** */
        $sale_collection = self::PaymentStockInforamtion($Sale, $request);
       
        return response()->json([
            'Sale' => $Sale,
            'Sale_payment' => $sale_collection,
            'SaleItems' => $items,
            'message' => 'Sale Saved!'
        ],200);

        
    }

    public static function update(SaleRequest $request, string $id)
    {
        if(count($request->itemsForDel) > 0) {
            SaleDetailController::SaledDeleteByArray($request->itemsForDel);
            //if($request->status === true) {
                StockController::StockDeleteByArrayIds($request->itemsForDel);
            //}
        }

        $Sale = Sale::findOrFail($id)->update([
            'cart_total_quantity' => $request->cart_total_quantity,
            'cart_total_amount' => $request->cart_total_amount,
            'discount' => $request->discount,
            'shipping' => $request->shipping,
            'vat' => $request->vat,
            'tax' => $request->tax,
            'status' => $request->status === true ? 1:0,
            'buyer_code' => $request->buyer_code,
            'updator_id' => $request->updator_id,
            'updated_at' => $request->updated_at,
        ]);

        $SaleItems = [];
        foreach($request->Sales as $key => $product) {
            $SaleItems[$key] = SaleDetailController::update($product);
        }

          /** **********Payment Information & Stock Declaration********** */
       self::PaymentStockInforamtion($id, $request);

        if($request->status === true) {
            //Stock update
            StockController::CreateOrUpdateStock($SaleItems);
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
            'Sale' => $Sale,
            'SaleItems' => $SaleItems,
            'message' => 'Sale Updated!',
        ],
        200
       );
    }

    public static function PaymentStockInforamtion($Sale_id, SaleRequest $request) {
        
        $receivables = [];
            $receivables['description_id'] = $request->inv;
            $receivables['payee_code'] = $request->supplier_code;
            $receivables['description'] = 'Sale';
            $receivables['type'] = 'Payment';
            $receivables['cart_total_amount'] = $request->cart_total_amount;
            $receivables['company_id'] = $request->company_id;
            $receivables['user_id'] = $request->user_id;
            $receivables['updator_id'] = $request->updator_id;
            $receivables['collection'] = $request->collection;

            $request->cart_total_amount !== $request->collection ? ReceivableController::CreateOrUpdatereceivable($receivables): ReceivableController::destroy($receivables);
            $Sale_payment = ReceivePaymentController::CreateOrUpdateReceivePayment($receivables);
            return $Sale_payment;
        
    }

    public function destroy(int $id) {
        $Sale = Sale::findOrFail($id);
        $item = [];
        $item['description_id'] = $Sale->inv;
        $item['company_id'] = $Sale->company_id;
        receivableController::destroy($item);
        ReceivePaymentController::destroy($item);
        $Sale->delete();
        return response()->json(
            ['message' => 'Sale Deleted!'],
            200
        );
    }

}
