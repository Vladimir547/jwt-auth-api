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
    public function update (Request $request) {

        $user = auth()->user();
        if (auth()->user() && auth()->user()->role->role !== 'admin' and empty($request->id)) {
            $request->validate([
                'id' => 'integer',
                'first_name' => 'min:2',
                'last_name' => 'min:2',
                'phone'=> '|regex:/^(\+?\d+)$/|min:8',
                'email'=> 'email|unique:users,email,'.auth()->user()->id,
            ]);
            $user->update([
                'first_name' => !empty($request->first_name) ? $request->first_name : auth()->user()->first_name,
                'last_name' => !empty($request->last_name) ? $request->last_name : auth()->user()->last_name,
                'phone' => !empty($request->phone) ? $request->phone : auth()->user()->phone,
                'email' => !empty($request->email) ? $request->email : auth()->user()->email,
                'password' => !empty($request->password) ? Hash::make($request->password) : auth()->user()->password,
            ]);
            dd($user);
        } elseif (auth()->user() && auth()->user()->role->role === 'admin' and !empty($request->id)) {

            $user = User::where("id", $request->id)->first();
            $request->validate([
                'id' => 'integer',
                'first_name' => 'min:2',
                'last_name' => 'min:2',
                'phone'=> '|regex:/^(\+?\d+)$/|min:8',
                'email'=> 'email|unique:users,email,'.$user->id,
            ]);
            $user->update([
                'first_name' => !empty($request->first_name) ? $request->first_name : $user->first_name,
                'last_name' => !empty($request->last_name) ? $request->last_name : $user->last_name,
                'phone' => !empty($request->phone) ? $request->phone : $user->phone,
                'email' => !empty($request->email) ? $request->email : $user->email,
                'password' => !empty($request->password) ? Hash::make($request->password) : $user->password,
            ]);
        }  else {
            return response()->json([
                "status" => false,
                "message" => "You don't have rights",
            ]);
        }
        return response()->json([
            "status" => true,
            "message" => "Updated",
        ]);
    }
}
