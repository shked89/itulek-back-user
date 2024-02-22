<?php

namespace App\Http\Controllers;
use \App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $result = $this->authService->register($validatedData);

        return response()->json(['user' => $result['user'], 'access_token' => $result['token'], 'token_type' => 'Bearer']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->authService->login($credentials);

        if (!$result) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json(['user' => $result['user'], 'access_token' => $result['token'], 'token_type' => 'Bearer']);
    }
}
