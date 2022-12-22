<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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

            if (!$user || !Hash::check($data["usu_pas"], $user->usu_pas)) {
                throw ValidationException::withMessages(['usu_usu' => 'credentials are incorrect']);
                
            }

            $token = $user->createToken($data["usu_usu"])->plainTextToken;

            return response()->json([
                "message" => "login successful",
                "auth_token" => $token,
                "token_type" => ""
            ]);
        }
    }

    public function logout()
    {
        $user = auth()->user();
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
            return response()->json([], 204);
        }
    }

    private function rules()
    {
        return [
            'usu_usu' => 'required|string',
            'usu_pas' => 'required|string'
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
