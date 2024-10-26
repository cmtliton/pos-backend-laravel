<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryControllers\User\UserController;
use App\Http\Controllers\InventoryControllers\User\CompanyController;
use App\Http\Controllers\InventoryControllers\Item\ItemController;
use App\Http\Controllers\InventoryControllers\Brand\BrandController;
use App\Http\Controllers\InventoryControllers\Party\BuyerController;
use App\Http\Controllers\InventoryControllers\Party\SupplierController;
use App\Http\Controllers\InventoryControllers\Product\ProductController;
use App\Http\Controllers\InventoryControllers\Product\ProductImageController;
use App\Http\Controllers\InventoryControllers\Product\StockController;
use App\Http\Controllers\InventoryControllers\Purchases\PurchaseController;
use App\Http\Controllers\InventoryControllers\Purchases\PurchaseDetailController;
use App\Http\Controllers\InventoryControllers\Sales\SaleController;
use App\Http\Controllers\InventoryControllers\Sales\SaleDetailController;
use App\Http\Controllers\InventoryControllers\Purchases\SlNoController;
use App\Http\Controllers\InventoryControllers\Accounts\AccountController;
use App\Http\Controllers\InventoryControllers\Accounts\LedgerController;
use App\Models\InventoryModels\Sale;

/** *************************** Open Route, all are accessable ******************* */

Route::post('login', [UserController::class, 'login']);
Route::get('getCompanies', [CompanyController::class,'index']);
Route::post('createCompany', [CompanyController::class,'store']);
Route::post('newUser', [UserController::class,'store']);
Route::get('getUserByUsername/{id}', [UserController::class,'getUsername']);
Route::get('getUserByMobileno/{id}', [UserController::class,'getMobileno']);


/** ************************************* Protected Route ************************* */
Route::group([
    "middleware" => ["auth:sanctum"]
], function (){
    /** *********************** User and Company Route **************************** */
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('users/{id}', [UserController::class,'show']);
    Route::put('UserUpdate/{id}/edit', [UserController::class, 'update']);
    Route::put('CompanyUpdate/{id}/edit', [CompanyController::class, 'CmpUpdate']);

    /** ************************ Item Route ************************************** */
    Route::post('createItem', [ItemController::class,'store']);
    Route::put('updateItem/{id}/update', [ItemController::class,'update']);
    Route::get('getItemByCmpId/{id}/Company', [ItemController::class,'getItemsByCmpId']);
    Route::get('deleteItem/{id}/delete', [ItemController::class,'destroy']);

    /** *************************** Brand Route ************************************* */
    Route::post('createBrand', [BrandController::class,'store']);
    Route::put('updateBrand/{id}/update', [BrandController::class,'update']);
    Route::get('getBrandByCmpId/{id}/Company', [BrandController::class,'getBrandsByCmpId']);
    Route::get('deleteBrand/{id}/delete', [BrandController::class,'destroy']);

    /** *************************** Product Route ************************************* */
    Route::post('createProduct', [ProductController::class,'store']);
    Route::put('updateProduct/{id}/update', [ProductController::class,'update']);
    Route::get('getProductByCmpId/{id}/Product', [ProductController::class,'getProductsByCmpId']);
    Route::get('deleteProduct/{id}/delete', [ProductController::class,'destroy']);

    Route::get('products/{product_id}/upload', [ProductImageController::class, 'index']);
    Route::post('products/{product_id}/upload', [ProductImageController::class, 'store']);
    Route::get('product-image/{product_imageId}/delete', [ProductImageController::class, 'destroy']);

     /** *************************** Purchase Route ************************************* */
     Route::get('purchaseinitialize/{id}/', [PurchaseController::class,'initializeId']);
     Route::post('createPurchase', [PurchaseController::class,'store']);
     Route::put('updatePurchase/{id}/update', [PurchaseController::class,'update']);
     Route::get('getPurchaseByCmpId/{id}/Purchase', [PurchaseController::class,'getPurchasesByCmpId']);
     Route::get('deletePurchase/{id}/delete', [PurchaseController::class,'destroy']);

     Route::get('getPurchaseByCmpId/{id}/PurchaseItems', [PurchaseDetailController::class,'getPurchaseDetailsByCmpId']);
     Route::post('deletePurchasedItem/delete', [PurchaseDetailController::class,'PurchasedDeleteByArray']);

     Route::post('getSlNosById/Purchased', [SlNoController::class,'getSlNosById']);

     /** *************************** Stock Route ************************************* */

     Route::get('checkStockForPurchaseDelete/{cmpid}/{id}/{qty}', [StockController::class,'toCheckDeletePurchase']);
     Route::get('getStocksByCmpId/{cmpid}/Stock', [StockController::class,'getStocksByCmpId']);
     Route::get('stockUpdating/{cmpid}/{id}/{qty}/', [StockController::class,'updatingStock']);

      /** *************************** Sale Route ************************************* */

      Route::get('saleinitialize/{cmpid}/', [SaleController::class,'initializeId']);
      Route::post('createSale', [SaleController::class,'store']);
      Route::put('updateSale/{id}/update', [SaleController::class,'update']);
      Route::get('getSaleByCmpId/{id}/Sale', [SaleController::class,'getSalesByCmpId']);
      Route::get('deleteSale/{id}/delete', [SaleController::class,'destroy']);
 
      Route::get('getSaleByCmpId/{id}/SaleItems', [SaleDetailController::class,'getSaleDetailsByCmpId']);
      Route::post('deleteSaledItem/delete', [SaleDetailController::class,'SaledDeleteByArray']);
 
      Route::get('getSlNosByProductId/{id}/Sell', [SlNoController::class,'getSlNosByProductId']);
      Route::get('getProductBySlNo/{id}/Sell', [SlNoController::class,'getProductBySlNo']);

    /** *************************** Supplier Route ************************************* */
    Route::post('createSupplier', [SupplierController::class,'store']);
    Route::put('updateSupplier/{id}/update', [SupplierController::class,'update']);
    Route::get('getSupplierByCmpId/{id}/Supplier', [SupplierController::class,'getSuppliersByCmpId']);
    Route::get('deleteSupplier/{id}/delete', [SupplierController::class,'destroy']);
    Route::get('getSupplierByMobileno/{cmpid}/{id}', [SupplierController::class,'getMobileno']);

    /** *************************** Customer Route ************************************* */
    Route::post('createBuyer', [BuyerController::class,'store']);
    Route::put('updateBuyer/{id}/update', [BuyerController::class,'update']);
    Route::get('getBuyerByCmpId/{id}/Buyer', [BuyerController::class,'getBuyersByCmpId']);
    Route::get('deleteBuyer/{id}/delete', [BuyerController::class,'destroy']);
    Route::get('getBuyerByMobileno/{cmpid}/{id}', [BuyerController::class,'getMobileno']);


     /** *************************** Accounts Route ************************************************************* */

     Route::get('getAccount_Types', [AccountController::class,'getAccountTypes']);
     Route::post('createAccount', [AccountController::class,'store']);
     Route::put('updateAccount/{id}/update', [AccountController::class,'update']);
     Route::get('getAccountByCmpId/{id}/Account', [AccountController::class,'getAccountsByCmpId']);
     Route::get('deleteAccount/{id}/delete', [AccountController::class,'destroy']);

     /** *************************** Ledgers/Transaction/Expenses/Income/Investment/ Route ************************************* */
     Route::get('Ledgerinitialize/{id}/', [LedgerController::class,'initializeId']);
     Route::post('createLedger', [LedgerController::class,'store']);
     Route::put('updateLedger/{id}/update', [LedgerController::class,'update']);
     Route::get('getLedgerByCmpId/{id}/Ledger', [LedgerController::class,'getLedgersByCmpId']);
     Route::get('deleteLedger/{id}/delete', [LedgerController::class,'destroy']);

    /** for Testing............ */
    
  
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
