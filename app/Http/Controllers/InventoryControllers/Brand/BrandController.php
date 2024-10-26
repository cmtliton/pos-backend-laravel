<?php

namespace App\Http\Controllers\InventoryControllers\Brand;

use App\Models\InventoryModels\Brand;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequests\Brand\BrandRequest;

class BrandController extends Controller
{
    public function getBrandsByCmpId($id) {
        $brands = Brand::where('company_id', $id)->get();
        return $brands;
    }

    public function store(BrandRequest $request) {
        $brand = Brand::create([
            'name' => $request->name,
            'status' => $request->status == true ? 1:0,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
        ]);
        return response()->json([
            'brand' => $brand,
            'message' => 'Brand Saved!'
        ],200);
    }

    public function update(BrandRequest $request, string $id)
    {
        $brand = Brand::findOrFail($id)->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);
       return response()->json(
        [
            'message' => 'Brand Updated!',
        ],
        200
       );
    }

    public function destroy(int $id) {
        $brand = Brand::findOrFail($id);
        $brand->delete();
        return response()->json(
            ['message' => 'Brand Deleted!'],
            200
        );
    }
}
