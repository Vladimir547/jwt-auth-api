<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewUser
{
    /**
     * Функция для создания новых ссылок
     * createService сервис который записывает ссылки
     *
     * @use Illuminate\Support\Facades\Hash;
     *
     * @param Illuminate\Http\Request;
     *
     * @return JsonResponse
     */
    public function addUser (Request $request) {
        $request->validate([
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'phone'=> 'required||regex:/^(\+\d+)$/|min:8',
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
            "status" => 200,
            "message" => "User created"
        ]);
    }
}
