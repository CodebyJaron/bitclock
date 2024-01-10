<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordStoreRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\PasswordValidateResetTokenRequest;
use App\Mail\PasswordChangedMail;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Hash;

class PasswordController extends Controller
{
    /**
     * Requests a password reset for the given email.
     *
     * @param PasswordStoreRequest $request
     *
     * @return Response
     */
    public function store(PasswordStoreRequest $request): Response
    {
        $validated = $request->validated();

        $token = Str::random(32);

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return response([
                'message' => 'Gebruiker niet gevonden.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user->reset_token = $token;
        $user->save();

        Mail::to($validated['email'])->send(new PasswordResetMail($user));

        return response([
            'message' => 'We hebben je een e-mail gestuurd met een link om je wachtwoord opnieuw te kunnen instellen.',
        ], Response::HTTP_OK);
    }

    /**
     * Reset the user's password.
     *
     * @param PasswordUpdateRequest $request
     *
     * @return Response
     */
    public function update(PasswordUpdateRequest $request): Response
    {
        $validated = $request->validated();

        $user = User::where('reset_token', $validated['reset_token'])->first();

        if (!$user) {
            return response([
                'message' => 'Reset token is onjuist.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $user->update([
                'password' => Hash::make($validated['password']),
                'reset_token' => null,
            ]);

            Mail::to($user['email'])->send(new PasswordChangedMail($user));

            return response([
                'message' => 'Je wachtwoord is met success aangepast.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response([
                'message' => 'Error bij het aanpassen van je wachtwoord.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Validate the reset token.
     *
     * @param PasswordValidateResetTokenRequest $request
     *
     * @return Response
     */
    public function validateResetToken(PasswordValidateResetTokenRequest $request): Response
    {
        $validated = $request->validated();

        $user = User::where('reset_token', $validated['reset_token'])->first();
        if (!$user) {
            return response([
                'message' => 'Reset token is ongeldig.
                Vraag opnieuw een wachtwoord reset aan of probeer het later opnieuw.',
            ], 404);
        }

        return response(['message', 'Je hebt een geldige reset token!'], 200);
    }
}
