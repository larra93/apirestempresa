<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([

            'name'     => 'required',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create([

            'name'    => $request->name,
            'email'   => $request->email,
            'password'=> Hash::make($request->password)
        ]);

       // $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'res' => true,
            'msj' => 'Registro guardado exitosamente'
        ],200);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas'],
            ]);
        }
    
        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json([
            'res' => true,
            'token' => $token
        ],200);
    }

    public function userInfo(Request $request)
    {
        return $request->user();
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'res' => true,
            'msg' => 'Token eliminado correctamente'
        ],200);
    }
}
