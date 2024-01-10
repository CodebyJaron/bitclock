<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Logs the user in.
     *
     * @param LoginRequest $request
     *
     * @return Response
     */
    public function store(LoginRequest $request): Response
    {
        $validated = $request->validated();

        $user = User::where('email', $validated['email'])->first();
        if (!$user || !Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return response([
                'message' => 'Onjuiste inloggegevens.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('bitclock')->plainTextToken;
        Auth::login($user);

        return response([
            'user' => new UserResource($user),
            'token' => $token,
        ], Response::HTTP_OK);
    }
}
