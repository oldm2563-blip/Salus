<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;


class AuthController extends Controller
{
    #[OA\Post(
        path: '/register',
        summary: 'Create a new account',
        security: [['sanctum' => []]],
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', format:'date' , example: 'Hamza'),
                    new OA\Property(property: 'email', type: 'string', example: 'hamza1234@gmail.com' ),
                    new OA\Property(property: 'password', type: 'string'),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 201, description: 'user created'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => 'required',
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => 'required',
        ]);
        $user = User::create($incomingFields);
        $token = $user->createToken('myToken')->plainTextToken;

        return response()->json([
            'success' => true,
                'message' => "you've been registered",
                'user' => $user,
                'token' => $token
        ]);
    }

    #[OA\Post(
        path: '/login',
        summary: 'Login',
        security: [['sanctum' => []]],
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'hamza1234@gmail.com' ),
                    new OA\Property(property: 'password', type: 'string'),
                ],
            ),
        ),
        
        responses: [
            new OA\Response(response: 200, description: 'user login'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);
        
        if (Auth::attempt($incomingFields)) {
            $token = auth()->user()->createToken('myToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'login was a success',
                'user' => Auth::user(),
                'token' => $token
            ]);
        } else {
            return response()->json([
            'success' => 'false',
            'message' => 'bad credentienls plz revise them'
        ]);
        }
    }
    #[OA\Post(
        path: '/logout',
        summary: 'Logout from account',
        security: [['sanctum' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(response: 200, description: 'user login'),
        ]
    )]
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'success' => 'true',
            'message' => 'you have logged out'
        ]);
    }
    #[OA\Get(
        path: '/me',
        summary: 'retrieve the loged in user info',
        security: [['sanctum' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(response: 200, description: 'user login'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function me(){
        return response()->json([
            'success' => 'true',
            'data' => auth()->user(),
            'message' => 'retrieve loged in user info'
        ]);
    }
}
