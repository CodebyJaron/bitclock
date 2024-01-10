<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Logs the user out.
     *
     * @return Response
     */
    public function store(Request $request): Response
    {
        // Revoke all of the user's tokens
        $request->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        // Logout the user
        Auth::guard('web')->logout();

        return response([
            'message' => 'Je bent uitgelogd.',
        ], Response::HTTP_OK);
    }
}
