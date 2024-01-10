<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStoreRequest;
use App\Http\Requests\RegisterValidateInviteTokenRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    /**
     * Registers the user.
     *
     * @param RegisterStoreRequest $request
     *
     * @return Response
     */
    public function store(RegisterStoreRequest $request): Response
    {
        $validated = $request->validated();

        $user = User::where('invite_token', $validated['invite_token'])->first();
        if (!$user) {
            return response([
                'message' => 'Uitnodigings code is ongeldig.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user->update($validated);
        $user->invite_token = null;
        $user->save();

        $token = $user->createToken('bitclock')->plainTextToken;

        return response([
            'user' => new UserResource($user),
            'token' => $token,
        ], Response::HTTP_CREATED);
    }

    /**
     * Validates the invite token and returns the user if valid.
     *
     * @param RegisterValidateInviteTokenRequest $request
     *
     * @return Response
     */
    public function userToRegister(User $user)
    {
        return new UserResource($user);
    }
}
