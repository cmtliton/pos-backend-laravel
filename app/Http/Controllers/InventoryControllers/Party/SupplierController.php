<?php

namespace App\Http\Controllers\InventoryControllers\Party;

use App\Http\Controllers\Controller;
use App\Models\InventoryModels\Supplier;
use App\Http\Requests\InventoryRequests\Party\SupplierStoreRequest;
//use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function getSuppliersByCmpId($id) {
        $suppliers = Supplier::where('company_id', $id)->get();
        return $suppliers;
    }

    public static function GenSupplierId() {
        $SupplierId = Supplier::max('id');
        if(empty($SupplierId)) {
            $number = 'S'.'000001';
            return $number;
        } else {
            $id = str_pad($SupplierId+1,6, 0, STR_PAD_LEFT);
            $number = 'S'.$id;
            return $number;
        }
    }

    public function store(SupplierStoreRequest $request) {
        $supplier = Supplier::create([
            'code' => self::GenSupplierId(),
            'name' => $request->name,
            'mobileno' => $request->mobileno,
            'addr' => $request->addr,
            'type' => $request->type,
            'status' => $request->status == true ? 1:0,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
            'updator_id' => $request->updator_id,
        ]);
        return response()->json([
            'Supplier' => $supplier,
            'message' => 'Supplier Saved!'
        ],200);
    }

    public function update(SupplierStoreRequest $request, string $id)
    {
        $Supplier = Supplier::findOrFail($id)->update([
            'name' => $request->name,
            'mobileno' => $request->mobileno,
            'addr' => $request->addr,
            'type' => $request->type,
            'status' => $request->status,
            'updator_id' => $request->updator_id,
        ]);
       return response()->json(
        [
            'message' => 'Supplier Updated!',
        ],
        200
       );
    }

    public function getMobileno(int $cmpid, int $id) {
        $supplier = Supplier::where('company_id', $cmpid)->where('mobileno', $id)->value('id');
        return $supplier;
    }

    public function destroy(int $id) {
        $Supplier = Supplier::findOrFail($id);
        $Supplier->delete();
        return response()->json(
            ['message' => 'Supplier Deleted!'],
            200
        );
    }
}
