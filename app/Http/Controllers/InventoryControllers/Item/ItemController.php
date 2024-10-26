<?php

namespace App\Http\Controllers\InventoryControllers\Item;

use App\Http\Controllers\Controller;
use App\Models\InventoryModels\Item;
use App\Http\Requests\InventoryRequests\Item\ItemStoreRequest;

class ItemController extends Controller
{
    public function getItemsByCmpId($id) {
        $items = Item::where('company_id', $id)->get();
        return $items;
    }

    public function store(ItemStoreRequest $request) {
        $item = Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status == true ? 1:0,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
        ]);
        return response()->json([
            'item' => $item,
            'message' => 'Item Saved!'
        ],200);
    }

    public function update(ItemStoreRequest $request, string $id)
    {
        $item = Item::findOrFail($id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status,
        ]);
       return response()->json(
        [
            'message' => 'Item Updated!',
        ],
        200
       );
    }

    public function destroy(int $id) {
        $item = Item::findOrFail($id);
        $item->delete();
        return response()->json(
            ['message' => 'Item Deleted!'],
            200
        );
    }
}
