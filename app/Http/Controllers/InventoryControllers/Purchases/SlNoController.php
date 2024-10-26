<?php

namespace App\Http\Controllers\InventoryControllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\InventoryModels\SlNo;

use Illuminate\Http\Request;

class SlNoController extends Controller
{
    public static function CreateOrUpdateSlNo ($Item) {
      $isSlNoExists = SlNo::where('id', $Item['id'])->first();
            if($isSlNoExists) {
                //update
                $isSlNoExists->update([
                    'purchasedetail_id' => $Item['purchasedetail_id'],
                    'product_id' => $Item['product_id'],
                    'slno' => $Item['slno'],
                    'status' => $Item['status'] === true ? 1:0,
                   ]);
            } else {
                //insert
                SlNo::create([
                    'purchasedetail_id' => $Item['purchasedetail_id'],
                    'product_id' => $Item['product_id'],
                    'slno' => $Item['slno'],
                    'status' => $Item['status'] === true ? 1:0,
                    'company_id' => $Item['company_id'],
                ]);
            }   
    }


    public static function getSlNosById(Request $request) {
        $allSlNos = [];
        foreach($request->slnos as $key => $slno) {
            $SlNos = SlNo::where('company_id', $slno['company_id'],)->where('purchasedetail_id', $slno['purchasedetail_id'])->where('product_id', $slno['product_id'])->get();
            foreach($SlNos as $no) {
              array_push($allSlNos, $no);
            }
        }
        return response()->json([
            'slnos' => $allSlNos,
        ], 200);
    }

    public static function getSlNosByProductId($product_id) {
        $products = SlNo::where('product_id', $product_id)->where('status', 0)->select('product_id', 'slno')->get();
        return $products;
    }
    public static function getProductBySlNo($SlNo) {
        $products = SlNo::where('slno', $SlNo)->where('status', 0)->select('product_id', 'slno')->first();
        return $products;
    }

    public static function SlNoDeleteByArrayIds($DelItemsFromCart) {
        foreach($DelItemsFromCart as $key => $item) {
        $itm = SlNo::where('purchasedetail_id', $item)->first();
        if($itm) {
            $itm->delete();
        }
        }

        return response()->json(
            ['message' => 'SlNo Deleted!'],
            200
        );
        }
}
