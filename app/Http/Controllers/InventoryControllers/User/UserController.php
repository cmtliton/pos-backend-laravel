<?php

namespace App\Http\Controllers\InventoryControllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\InventoryRequests\User\UserRegistrationRequest;
use App\Http\Requests\InventoryRequests\User\LoginUserRequest;
use App\Http\Requests\InventoryRequests\User\UserUpdateRequest;
use App\Models\InventoryModels\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->get();
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRegistrationRequest $request)
    {
        $user = User::create([
            'company_id' => $request->company_id,
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'mobileno' => $request->mobileno,
            'addr' => $request->addr,
            'role' => $request->role,
            'status' => $request->status,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ],200);
    }
    public function login(LoginUserRequest $request)
    {
     $user = User::where('username', $request->username)->first();

     if(!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'message'=> 'Username or Password incorrect!'
        ], 401);
     }

     $token = $user->createToken('auth_token')->plainTextToken;
     return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
     ], 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->currentAccessToken()->delete();
       return response()->json([
        'message' => 'User Signout!'
       ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::where('company_id', $id)->get();
        return $user;
    }

    public function getUsername($id)
    {
       $user = User::where('username', $id)->value('id');
        return $user;
    }
    public function getMobileno($id)
    {
       $user = User::where('mobileno', $id)->value('id');
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::findOrFail($id)->update([
            'name' => $request->name,
            'mobileno' => $request->mobileno,
            'addr' => $request->addr,
            'role' => $request->role,
            'status' => $request->status == true ? 1:0,
        ]);
       return response()->json(
        ['message' => 'User Updated!'],
       );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
