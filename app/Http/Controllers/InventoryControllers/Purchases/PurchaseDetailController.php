<?php

namespace App\Http\Controllers\InventoryControllers\Purchases;

use App\Http\Controllers\Controller;
use App\Models\InventoryModels\PurchaseDetail;
//use Illuminate\Http\Request;

class PurchaseDetailController extends Controller
{
    public function getPurchaseDetailsByCmpId($id) {
        $PurchaseItems = PurchaseDetail::where('company_id', $id)->latest()->orderBy('created_at', 'desc')->get();
        return $PurchaseItems;
    }

    public static function store($purchase, $product) {
        $purchasedetail = PurchaseDetail::create([
            'purchase_id' => $purchase->id,
            'PRN' => $purchase->PRN,
            'product_id' => $product['product_id'],
            'purchase_price' => $product['purchase_price'],
            'mrp' => $product['mrp'],
            'quantity' => $product['quantity'],
            'company_id' => $purchase->company_id,
        ]);
        return $purchasedetail;
        
    }

    public static function update($purchase) {
            $isPurchase = PurchaseDetail::where('id', $purchase['id'])->first();
            if ($isPurchase) {
                //update
                $isPurchase->update([
                 'purchase_price' => $purchase['purchase_price'],
                 'mrp' => $purchase['mrp'],
                 'quantity' => $purchase['quantity'],
                ]);
                return $purchase;
            } else {
                //insert
                $purchasedetail = PurchaseDetail::create([
                    'purchase_id' => $purchase['purchase_id'],
                    'PRN' => $purchase['PRN'],
                    'product_id' => $purchase['product_id'],
                    'purchase_price' => $purchase['purchase_price'],
                    'mrp' => $purchase['mrp'],
                    'quantity' => $purchase['quantity'],
                    'company_id' => $purchase['company_id'],
                ]);
                return $purchasedetail;
        }    
    }
public static function PurchasedDeleteByArray($DelItemsFromCart) { /** products item delete */
    foreach($DelItemsFromCart as $key => $item) {
    $itm = PurchaseDetail::findOrFail($item);
    if($itm) {
        /** Delete also slnos table  */
        $itm->delete();
    }
    }
    return response()->json(
        ['message' => 'Item Deleted!'],
        200
    );
    }
}
