<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use DB;
class ApiController extends Controller
{
    public function registration (Request $request) {
        $request->validate([
           'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'phone'=> 'required||regex:/^(\+?\d+)$/|min:8',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|confirmed|min:6',
        ]);
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2
        ]);
        return response()->json([
            "status" => true,
            "message" => "User created"
        ]);
    }
    public function login(Request $request){

        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $token = JWTAuth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);
        if(!empty($token)) {
            return response()->json([
                "status" => true,
                "message" => "User logged in succcessfully",
                "token" => $token
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Something went wrong",
            ]);
        }
    }
    public function profile () {
        $user = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => $user->phone,
                'email' => $user->email,
                'role_id' => $user->role->role
            ]
        ]);
    }
    public function logout () {
        auth()->logout();
        return response()->json([
            "status" => true,
            "message" => "User logged out",
        ]);
    }
}
