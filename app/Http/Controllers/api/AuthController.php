<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => 'required',
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => 'required',
        ]);
        $user = User::create($incomingFields);
        $token = $user->createToken('myToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        if (Auth::attempt($incomingFields)) {
            $token = Auth::user()->createToken('myToken')->plainTextToken;
            return [
                'message' => 'login was a success',
                'user' => Auth::user(),
                'token' => $token
            ];
        } else {
            return [
                'message' => 'check youre credentiels'
            ];
        }
    }
    public function logout(Request $request)
    {
        Auth::user()->tokens()->delete();
        return [
            'message' => 'You have logged out'
        ];
    }
}
