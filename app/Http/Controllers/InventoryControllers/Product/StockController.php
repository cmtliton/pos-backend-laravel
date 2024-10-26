<?php

namespace App\Http\Controllers\InventoryControllers\Product;

use App\Models\InventoryModels\Stock;
use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;

class StockController extends Controller
{
    public static function getStocksByCmpId($company_id) {
        $stocks = Stock::where('company_id', $company_id)
        ->select('product_id', 'purchase_price', 'mrp', Stock::raw('SUM(quantity) as stock_qty'))
        ->groupBy('product_id', 'purchase_price', 'mrp')->get();
        return $stocks;
    }

    public static function updatingStock($company_id, $product_id, $qty) {
        $stock = Stock::where('company_id', $company_id)->where('product_id', $product_id)
                        ->selectRaw('sum(quantity) as stock')->groupBy('product_id')->first();

        if($stock->stock>=$qty){
            $products = Stock::where('company_id', $company_id)->where('product_id', $product_id)->get();
        $items=[];
        $saleqty = $qty;
        foreach($products as $prd) {
            if($prd->quantity < $saleqty) {
                $stockqty = $prd->quantity-$prd->quantity;
                $items['id'] = $prd->purchasedetail_id;
                $items['quantity'] = $stockqty;
                self::updatedStock($items);
                $saleqty -= $prd->quantity;
            }else {
                $stockqty = $prd->quantity-$saleqty;
                $items['id'] = $prd->purchasedetail_id;
                $items['quantity'] = $stockqty;
                self::updatedStock($items);
                break;
            }
        }
        } else {
            return response()->json(
                ['message' => 'Product Insuffcient!'],
                200
            );
        }             
        
    }

    public static function updatedStock($Stocks) {
        $isStock = Stock::where('purchasedetail_id', $Stocks['id'])->first();
        if($isStock){
            $isStock->update([
                'quantity' => $Stocks['quantity']
            ]);
        }
        Stock::where('quantity', '=', 0)->delete();
    }

    public static function CreateOrUpdateStock ($Stocks) {
        foreach($Stocks as $id => $Stock) {
            $id = $Stock['id'] ? $Stock['id'] : '';
            $isStock = Stock::where('purchasedetail_id', $id)->first();
            if($isStock) {
                //update
                $isStock->update([
                    'purchase_id' => $Stock['purchase_id'],
                    'PRN' => $Stock['PRN'],
                    'product_id' => $Stock['product_id'],
                    'quantity' => $Stock['quantity'],
                    'purchase_price' => $Stock['purchase_price'],
                    'mrp' => $Stock['mrp'],
                   ]);
            } else {
                //insert
                Stock::create([
                    'purchasedetail_id' => $Stock['id'],
                    'purchase_id' => $Stock['purchase_id'],
                    'PRN' => $Stock['PRN'],
                    'product_id' => $Stock['product_id'],
                    'quantity' => $Stock['quantity'],
                    'purchase_price' => $Stock['purchase_price'],
                    'mrp' => $Stock['mrp'],
                    'company_id' => $Stock['company_id'],
                ]);
            }
        }
        
    }

    public static function StockDeleteByArrayIds($DelItemsFromCart) {
        foreach($DelItemsFromCart as $key => $item) {
        $itm = Stock::where('purchasedetail_id', $item)->first();
        if($itm) {
            $itm->delete();
        } else {
            echo 'There is no stock for deleting!';
        }
        }

        return response()->json(
            ['message' => 'Stock Deleted!'],
            200
        );
        }

    public static function toCheckDeletePurchase($company_id, $id, $qty) {
     $stock = Stock::where('company_id', $company_id)->where('purchase_id', $id)->sum('quantity');

     if($stock === $qty) {
        return 1;
     } else {
        return 0;
     }
     
    }
}
