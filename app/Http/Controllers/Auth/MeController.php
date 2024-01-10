<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Returns the currently authenticated user.
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        return response(new UserResource($request->user()), Response::HTTP_OK);
    }
}
