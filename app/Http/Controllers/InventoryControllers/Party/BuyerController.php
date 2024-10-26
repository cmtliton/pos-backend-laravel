<?php

namespace App\Http\Controllers\InventoryControllers\Party;

use App\Models\InventoryModels\Buyer;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequests\Party\BuyerStoreRequest;


class BuyerController extends Controller
{
    public function getBuyersByCmpId($id) {
        $buyers = Buyer::where('company_id', $id)->get();
        return $buyers;
    }

    public function getMobileno(int $cmpid, int $id) {
        $buyer = Buyer::where('company_id', $cmpid)->where('mobileno', $id)->value('id');
        return $buyer;
    }

    public static function GenBuyerId() {
        $BuyerId = Buyer::max('id');
        if(empty($BuyerId)) {
            $number = 'C'.'000001';
            return $number;
        } else {
            $id = str_pad($BuyerId+1,6, 0, STR_PAD_LEFT);
            $number = 'C'.$id;
            return $number;
        }
    }

    public function store(BuyerStoreRequest $request) {
        $code = self::GenBuyerId();
        $Buyer = Buyer::create([
            'code' => $code,
            'name' => $request->name,
            'mobileno' => $request->mobileno,
            'addr' => $request->addr,
            'type' => $request->type,
            'status' => $request->status == true ? 1:0,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
        ]);
        return response()->json([
            'Buyer' => $Buyer,
            'message' => 'Buyer Saved!'
        ],200);
    }

    public function update(BuyerStoreRequest $request, string $id)
    {
        $Buyer = Buyer::findOrFail($id)->update([
            'name' => $request->name,
            'mobileno' => $request->mobileno,
            'addr' => $request->addr,
            'type' => $request->type,
            'status' => $request->status,
            'updator_id' => $request->updator_id,
        ]);
       return response()->json(
        [
            'message' => 'Buyer Updated!',
        ],
        200
       );
    }

    public function destroy(int $id) {
        $Buyer = Buyer::findOrFail($id);
        $Buyer->delete();
        return response()->json(
            ['message' => 'Buyer Deleted!'],
            200
        );
    }
}
