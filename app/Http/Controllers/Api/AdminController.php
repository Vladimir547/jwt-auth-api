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
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Все пользователи для админа",
     *     description="Только для админа",
     *     tags={"Все пользователи для админа"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Профиль пользователя",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 200}, summary="get users."),
     *         )
     *     ),
     *      @OA\Response(
     *          response="400",
     *          description="Не прав",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 400}, summary="You don't have rights."),
     *         )
     *     )
     * )
     * Контроллер вывода всех пользователей для админа.
     *
     * @return  JsonResponse
     */
    public function getAll () {
        if (auth()->user()->role->role === 'admin') {
            $users = User::all();
            return response()->json([
                "status" => 200,
                "users" => $users
            ]);
        }
        return response() ->json([
            "status" => 400,
            "message" => "You don't have rights"
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/admin/add",
     *     summary="Добавление пользователя",
     *     description="Только для админа",
     *     tags={"Добавление пользователя"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 ),
     *                 example={"first_name": "a3fb6", "last_name": "Jessica Smith", "phone": "+12345678", "email": "example@email.com", "password": "1234F+w#","password_confirmation": "1234F+w#"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Пользователь создан",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 200}, summary="User created."),
     *         )
     *     )
     * )
     * Контроллер создания пользователя для админа.
     *
     * @param Illuminate\Http\Request;
     *
     * @return  JsonResponse
     */
    public function addUser (Request $request, NewUser $new) {

        if (auth()->user()->role->role === 'admin') {
            return $new -> addUser($request);
        }
        return response() ->json([
            "status" => false,
            "message" => "You don't have rights"
        ]);
    }
    /**
     * @OA\Post(
     *     path="/api/admin/delete",
     *     summary="Удаление",
     *     description="Только для админа",
     *     tags={"Удаление"},
     *      security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="string"
     *                 ),
     *                 example={"id": 1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Пользователь создан",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 200}, summary="User created."),
     *         )
     *     ),
     *      @OA\Response(
     *          response="400",
     *          description="Нет прав",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 400}, summary="You don't have rights."),
     *         )
     *     )
     * )
     * Контроллер удаления пользопателей для админа.
     *
     * @param Illuminate\Http\Request;
     *
     * @return  JsonResponse
     */
    public function delete(Request $request) {
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
