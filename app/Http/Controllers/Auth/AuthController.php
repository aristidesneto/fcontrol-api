<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function me(): JsonResponse
    {
        $user = User::where('email', auth()->user()->email)->first();

        if (! $user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        return response()->json(['user' => new UserResource($user)], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciais informadas estão inválidas'], 401);
        }

        $token = $user->createToken('Laravel Password Grant Client')->plainTextToken;

        $cookie = $this->getCookieDetails($token);

        return response()->json(['user' => new UserResource($user), 'token' => $token], 200)
            ->withCookie(
                $cookie['name'],
                $cookie['value'],
                $cookie['minutes'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure']
            );
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        $cookie = Cookie::forget('cf_token');

        return response()->json([], 200)->withCookie($cookie);
    }

    private function getCookieDetails(string $token): array
    {
        return [
            'name' => 'cf_token',
            'value' => $token,
            'minutes' => 1440,
            'path' => null,
            'domain' => 'localhost',
            'secure' => true, // for production
            // 'secure' => null, // for localhost
            'httponly' => false,
            'samesite' => false,
        ];
    }
}