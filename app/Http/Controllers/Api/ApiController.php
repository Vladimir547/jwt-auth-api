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

/**
 * @OA\Info(
 *    title="APIs For Thrift Store",
 *    version="1.0.0",
 * ),
 *   @OA\SecurityScheme(
 *       securityScheme="bearerAuth",
 *       name="bearerAuth",
 *       type="http",
 *       scheme="bearer",
 *       bearerFormat="JWT",
 *    ),
 */
class ApiController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/registration",
     *     summary="Регистрация(отключенно, в задании этого не было)",
     *     description="Регистрация(отключенно, в задании этого не было)",
     *     tags={"Регистрация(отключенно, в задании этого не было)"},
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
     *
     * Контроллер регистрации
     *
     * @param Illuminate\Http\Request;
     * @param App\Service\NewUser;
     *
     * @return  JsonResponse
     */
    public function registration (Request $request,  NewUser $new) {
         return $new -> addUser($request);
    }
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Авторизация",
     *     description="Авторизация",
     *     tags={"Авторизация"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "example@email.com", "password": "1234F+w#"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Пользователь создан",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 200}, summary="User logged in succcessfully."),
     *         )
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Что-то пошло не так",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 400}, summary="Something went wrong."),
     *         )
     *     )
     * )
     *
     *
     * Контроллер авторизации
     *
     * @param Illuminate\Http\Request;
     *
     * @return  JsonResponse
     */
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
                "status" => 200,
                "message" => "User logged in succcessfully",
                "token" => $token
            ]);
        } else {
            return response()->json([
                "status" => 400,
                "message" => "Something went wrong",
            ]);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Профиль пользователя",
     *     description="Профиль пользователя",
     *     tags={"Профиль пользователя"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Профиль пользователя",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 200}, summary="User logged out."),
     *         )
     *     ),
     *      @OA\Response(
     *          response="401",
     *          description="Не авторизован",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 401}, summary="Unauthenticated."),
     *         )
     *     )
     * )
     *
     * Контроллер получения профиля пользователя
     *
     * @return  JsonResponse
     */
    public function profile () {
        $user = auth()->user();

        return response()->json([
            "status" => 200,
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
    /**
     * @OA\Get(
     *     path="/api/logout",
     *     summary="Выход из системы",
     *     description="Выход из системы",
     *     tags={"Выход из системы"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Профиль пользователя",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 200}, summary="Get user data."),
     *         )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Не авторизован",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 401}, summary="Unauthenticated."),
     *         )
     *     )
     * )
     * Контроллер выхода из кабинета
     *
     * @return  JsonResponse
     */
    public function logout () {
        auth()->logout();
        return response()->json([
            "status" => 200,
            "message" => "User logged out",
        ]);
    }
    /**
     * @OA\Post(
     *     path="/api/update",
     *     summary="Изменение пользователся",
     *     description="Изменение пользователся",
     *     tags={"Изменение пользователся"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                     property="id",
     *                     type="string"
     *                 ),
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
     *                 example={"id": 1, "first_name": "a3fb6", "last_name": "Jessica Smith", "phone": "+12345678", "email": "example@email.com", "password": "1234F+w#"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="Успешно изменен",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 200}, summary="Updated."),
     *         )
     *     ),
     *     @OA\Response(
     *          response="401",
     *          description="Не авторизован",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 401}, summary="Unauthenticated."),
     *         )
     *     ),
     *     @OA\Response(
     *          response="400",
     *          description="Не прав",
     *         @OA\JsonContent(
     *             @OA\Examples(example="result", value={"status": 400}, summary="You don't have rights."),
     *         )
     *     )
     * )
     *
     * Контроллер редоктирования пользопателей.
     * Пользователь может редактировать свои данные, так же администратор может редоктировать всех
     *
     * @param Illuminate\Http\Request;
     *
     * @return  JsonResponse
     */
    public function update (Request $request) {

        $user = auth()->user();
        // проверка роли
        if (auth()->user() && auth()->user()->role->role !== 'admin') {

            $request->validate([
                'id' => 'integer',
                'first_name' => 'min:2',
                'last_name' => 'min:2',
                'phone'=> '|regex:/^(\+\d+)$/|min:8',
                'email'=> 'email|unique:users,email,'.auth()->user()->id,
                'password'=> 'min:6',
            ]);
            // проверка на то что-бы пользоватеть не поменял другого пользователя
            if( !empty($request->id) && auth()->user()->id !== intval($request->id)) {
                return response()->json([
                    "status" => false,
                    "message" => "You don't have rights",
                ]);
            }
            // обновление данных
            $user->update([
                'first_name' => !empty($request->first_name) ? $request->first_name : auth()->user()->first_name,
                'last_name' => !empty($request->last_name) ? $request->last_name : auth()->user()->last_name,
                'phone' => !empty($request->phone) ? $request->phone : auth()->user()->phone,
                'email' => !empty($request->email) ? $request->email : auth()->user()->email,
                'password' => !empty($request->password) ? Hash::make($request->password) : auth()->user()->password,
            ]);
            //проверка является ли пользователь админом
        } elseif (auth()->user() && auth()->user()->role->role === 'admin') {

            $user = !empty($request->id) ? User::where("id", $request->id)->first() : auth()->user();
            $request->validate([
                'id' => 'integer',
                'first_name' => 'min:2',
                'last_name' => 'min:2',
                'phone'=> '|regex:/^(\+\d+)$/|min:8',
                'email'=> 'email|unique:users,email,'.$user->id,
                'password'=> 'min:6',
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
                "status" => 400,
                "message" => "You don't have rights",
            ]);
        }
        return response()->json([
            "status" => 200,
            "message" => "Updated",
        ]);
    }
}
