<?php

namespace App\Http\Controllers\InventoryControllers\Product;

use App\Http\Controllers\Controller;
use App\Models\InventoryModels\Product;
use App\Models\InventoryModels\Purchase;
use App\Models\InventoryModels\PurchaseDetail;
use App\Models\InventoryModels\Stock;
use App\Models\InventoryModels\ReceivePayment;
use App\Http\Requests\InventoryRequests\Product\ProductStoreRequest;
use App\Http\Controllers\InventoryControllers\Purchases\PurchaseController;

class ProductController extends Controller
{
    public function getProductsByCmpId($id) {
        $Products = Product::where('company_id', $id)->get();
        return $Products;
    }

    public function store(ProductStoreRequest $request) {
        $Product = Product::create([
            'item_id' => $request->item_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'code' => $request->code,
            'purchase_price' => $request->purchase_price,
            'mrp' => $request->mrp,
            'quantity' => $request->quantity,
            'warranty' => $request->warranty,
            'measuring_unit' => $request->measuring_unit,
            'status' => $request->status == true ? 1:0,
            'isPublished' => $request->status == true ? 1:0,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
            'updator_id' => $request->user_id,
        ]);

        $purchasedetail = 0;

        if($Product->quantity > 0) {
            // Create Purchase 
            //Stock Update
            //receivespayments Table debited
            $purchasedetail = $this->createPurchase($Product);
        }

        return response()->json([
            'Product' => $Product,
            'PurchaseItems' => $purchasedetail,
            'message' => 'Product Saved!'
        ],200);

        
    }

    public function update(ProductStoreRequest $request, string $id)
    {
        $Product = Product::findOrFail($id)->update([
            'item_id' => $request->item_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'code' => $request->code,
            'purchase_price' => $request->purchase_price,
            'mrp' => $request->mrp,
            'quantity' => $request->quantity,
            'warranty' => $request->warranty,
            'measuring_unit' => $request->measuring_unit,
            'status' => $request->status == true ? 1:0,
            'isPublished' => $request->status == true ? 1:0,
            'updator_id' => $request->updator_id,
        ]);
       return response()->json(
        [
            'message' => 'Product Updated!',
        ],
        200
       );
    }

    public function destroy(int $id) {
        $Product = Product::findOrFail($id);
        $Product->delete();
        return response()->json(
            ['message' => 'Product Deleted!'],
            200
        );
    }

    public static function createPurchase($Product) {
        $PRN = PurchaseController::initializeId($Product->company_id);
        $purchase = Purchase::create([
            'PRN' => $PRN,
            'cart_total_quantity' => $Product->quantity,
            'cart_total_amount' => $Product->quantity * $Product->purchase_price,
            'status' => 1,
            'company_id' => $Product->company_id,
            'user_id' => $Product->user_id,
            'updator_id' => $Product->user_id,
            'created_at' => $Product->created_at,
            'updated_at' => $Product->updated_at
        ]);
        $purchasedetail = PurchaseDetail::create([
            'PRN' => $PRN,
            'purchase_id' => $purchase->id,
            'product_id' => $Product->id,
            'purchase_price' => $Product->purchase_price,
            'mrp' => $Product->mrp,
            'quantity' => $Product->quantity,
            'company_id' => $Product->company_id,
        ]);

        $stock = Stock::create([
            'purchasedetail_id' => $purchasedetail->id,
            'purchase_id' => $purchase->id,
            'PRN' => $PRN,
            'product_id' => $Product->id,
            'quantity' => $Product->quantity,
            'purchase_price' => $Product->purchase_price,
            'mrp' => $Product->mrp,
            'company_id' => $Product->company_id,
        ]);
        $receivepayment = ReceivePayment::create([
            'description_id' => $PRN,
            'description' => 'Purchase',
            'type' => 'Payment',
            'amount' => $purchase->cart_total_amount,
            'company_id' => $Product->company_id,
            'user_id' => $Product->user_id,
            'updator_id' => $Product->updator_id,
        ]);

        return $purchasedetail;
    }
}
