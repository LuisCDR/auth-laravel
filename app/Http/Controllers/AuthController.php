<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = validator($request->all(), $this->rules(), [], $this->attributes());
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            $data = $validator->validated();
            $user = User::where('usu_usu', $data["usu_usu"])->first();
            if (is_null($user)) return abort(404, 'Not Found');

            $user->tokens()->delete();

            // if (!$user || !Hash::check($data["usu_pas"], $user->usu_pas)) {
            //     throw ValidationException::withMessages(['usu_usu' => 'credentials are incorrect']);
                
            // }
            try {
                // $user = User::where('usu_usu', $request->usu_usu)->first();

                if ($user && Hash::check($data['usu_pas'], $user->usu_pas)) {
                    Auth::login($user);
                    $token = $user->createToken($user->usu_usu)->plainTextToken;
                    return response()->json([
                        "message" => "login successful",
                        "auth_token" => $token,
                        "token_type" => "Bearer"
                    ]);
                }
                return "credentials are incorrect";

            } catch (\Throwable $th) {
                return $th->getTrace();
            }

        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            "message" => "logged out successfully"
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = validator($request->all(), $this->rules(), [], $this->attributes());
        if ($validator->fails()) {
            return $validator->errors();
        } else {
            $data = $validator->validated();
            $user = (new CreateNewUser)->create($data);
            return response()->json([], 201);
        }
    }

    public function unauthorized()
    {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no autenticado',
            'data' => []
        ], 401);
    }

    private function rules()
    {
        return [
            'usu_usu' => 'required|string',
            'usu_pas' => 'required|string',
            'per_ide' => [
                Rule::requiredIf(request()->routeIs('/register'))
                ]
        ];
    }
    private function attributes()
    {
        return [
            'usu_usu' => 'USER',
            'usu_pas' => 'PASSWORD'
        ];
    }
}
