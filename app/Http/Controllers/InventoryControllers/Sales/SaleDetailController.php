<?php

namespace App\Http\Controllers\InventoryControllers\Sales;
use App\Models\InventoryModels\SaleDetail;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    public function getSaleDetailsByCmpId($id) {
        $SaleItems = SaleDetail::where('company_id', $id)->latest()->orderBy('created_at', 'desc')->get();
        return $SaleItems;
    }

    public static function store($Sale, $product) {
        $Saledetail = SaleDetail::create([
            'Sale_id' => $Sale->id,
            'PRN' => $Sale->PRN,
            'product_id' => $product['product_id'],
            'Sale_price' => $product['Sale_price'],
            'mrp' => $product['mrp'],
            'quantity' => $product['quantity'],
            'company_id' => $Sale->company_id,
        ]);
        return $Saledetail;
        
    }

    public static function update($Sale) {
            $isSale = SaleDetail::where('id', $Sale['id'])->first();
            if ($isSale) {
                //update
                $isSale->update([
                 'Sale_price' => $Sale['Sale_price'],
                 'mrp' => $Sale['mrp'],
                 'quantity' => $Sale['quantity'],
                ]);
                return $Sale;
            } else {
                //insert
                $Saledetail = SaleDetail::create([
                    'Sale_id' => $Sale['Sale_id'],
                    'PRN' => $Sale['PRN'],
                    'product_id' => $Sale['product_id'],
                    'Sale_price' => $Sale['Sale_price'],
                    'mrp' => $Sale['mrp'],
                    'quantity' => $Sale['quantity'],
                    'company_id' => $Sale['company_id'],
                ]);
                return $Saledetail;
        }    
    }
public static function SaledDeleteByArray($DelItemsFromCart) { /** products item delete */
    foreach($DelItemsFromCart as $key => $item) {
    $itm = SaleDetail::findOrFail($item);
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
