<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $registerData = $request->validated();
        $registerData['password'] = Hash::make($registerData['password']);
        $user = User::create($registerData);
        $token = Auth::login($user);

        return $this->jsonResponseWithToken($token);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return $this->jsonResponseWithToken($token);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'logout' => true
        ]);
    }

    public function me()
    {
        return response()->json(['user' => Auth::user()]);
    }

    //helper methodes
    private function jsonResponseWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'user' => Auth::user()
        ]);
    }
}
