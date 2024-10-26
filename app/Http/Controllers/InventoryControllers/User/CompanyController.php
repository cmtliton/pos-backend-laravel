<?php

namespace App\Http\Controllers\InventoryControllers\User;

use App\Http\Controllers\Controller;
use App\Models\InventoryModels\Company;
use App\Http\Requests\InventoryRequests\User\CreateCompanyRequest;
use App\Http\Requests\InventoryRequests\User\CmpUpdateRequest;
//use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index() {
        $companies = Company::latest()->get();
        return $companies;
    }
    public function store(CreateCompanyRequest $request) {
        $company = Company::create([
            'name' => $request->name,
            'mobileno' => $request->mobileno,
            'addr' => $request->addr,
            'status' => $request->status,

        ]);
        return response()->json($company, 200);
    }

    public function CmpUpdate(CmpUpdateRequest $request, string $id)
    {
             Company::findOrFail($id)->update([
            'name' => $request->name,
            'mobileno' => $request->mobileno,
            'addr' => $request->addr,
            'status' => $request->status == true ? 1:0,
        ]);
       return response()->json(
        ['message' => 'Company Profile Updated'],
       );
    }
}
