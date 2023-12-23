<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Service\NewUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use DB;
class ApiController extends Controller
{
    public function registration (Request $request,  NewUser $new) {
         return $new -> addUser($request);
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
