<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'The provided credentials are incorrect (email or password missing).',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! \Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect (user nt found).',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token deleted',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:10',
            ]);
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'The provided user data is incorrect.',
                'errors' => $e->errors(),
            ], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

}
