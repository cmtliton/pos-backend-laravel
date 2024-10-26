<?php

namespace App\Http\Controllers\InventoryControllers\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryModels\Account;
use App\Models\InventoryModels\AccountType;

class AccountController extends Controller
{
    public function getAccountsByCmpId($id) {
        $Accounts = Account::where('company_id', $id)->get();
        return $Accounts;
    }

    public function getAccountTypes() {
        $AccountTypes = AccountType::get();
        return $AccountTypes;
    }

    public function store(Request $request) {
        $Account = Account::create([
            'name' => $request->name,
            'description' => $request->description,
            'account_type_id' => $request->account_type_id,
            'status' => $request->status == true ? 1:0,
            'company_id' => $request->company_id,
            'user_id' => $request->user_id,
            'updator_id' => $request->updator_id,
        ]);
        return response()->json([
            'Account' => $Account,
            'message' => 'Account Saved!'
        ],200);
    }

    public function update(Request $request, string $id)
    {
        $Account = Account::findOrFail($id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'account_type_id' => $request->account_type_id,
            'status' => $request->status == true ? 1:0,
            'updator_id' => $request->updator_id,
        ]);
       return response()->json(
        [
            'message' => 'Account Updated!',
        ],
        200
       );
    }

    public function destroy(int $id) {
        $Account = Account::findOrFail($id);
        $Account->delete();
        return response()->json(
            ['message' => 'Account Deleted!'],
            200
        );
    }
}
