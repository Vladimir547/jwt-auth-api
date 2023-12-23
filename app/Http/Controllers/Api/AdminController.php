<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Service\NewUser;

class AdminController extends Controller
{
    public function getAll () {
        if (auth()->user()->role->role === 'admin') {
            $users = User::all();
            return response()->json([
                "status" => true,
                "users" => $users
            ]);
        }
        return response() ->json([
            "status" => false,
            "message" => "You don't have rights"
        ]);
    }
    public function addUser (Request $request, NewUser $new) {

        if (auth()->user()->role->role === 'admin') {
            return $new -> addUser($request);
        }
        return response() ->json([
            "status" => false,
            "message" => "You don't have rights"
        ]);
    }
    public function delete (Request $request) {
        $request->validate([
            'id' => 'required|integer',
        ]);
        if (auth()->user()->role->role === 'admin') {
            $user = User::where('id', $request->id)->first()->delete();
            return response() ->json([
                "status" => true,
                "message" => "Deleted"
            ]);
        }
        return response() ->json([
            "status" => false,
            "message" => "You don't have rights"
        ]);
    }
}
