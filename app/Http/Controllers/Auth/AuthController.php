<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\UserRegistered;
use Illuminate\Validation\Rules;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function me(): UserResource
    {
        if (! Auth::check()) {
            abort(401, "Não autorizado");
        }
        
        $user = User::where('email', auth()->user()->email)->first();

        return new UserResource($user);
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

        // $cookie = $this->getCookieDetails($token);

        return response()->json(['user' => new UserResource($user), 'token' => $token], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        $cookie = Cookie::forget('cf_token');

        return response()->json([], 200)->withCookie($cookie);
    }

    public function register(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'uuid' => Str::uuid()->toString(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        UserRegistered::dispatch($user);
        // event(new Registered($user));

        // Auth::login($user);

        return response()->noContent();
    }

    public function verifyEmail(string $uuid)
    {
        $user = User::whereUuid($uuid)->first();

        if (! $user) {
            abort(404, 'Não foi possível validar seu e-mail');
        }

        if ($user->email_verified_at) {
            return response()->json(['message' => 'OK'], 200);
        }

        $user->email_verified_at = now();
        $user->save();

        return new UserResource($user);
    }
}