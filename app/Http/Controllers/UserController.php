<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\InviteUser;
use Str;
use Mail;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function store(StoreUserRequest $request)
    {

        $validated = $request->validated();

        $userExists = User::where('email', $request->email)->exists();

        if ($userExists) {
            return response()->json(['message' => 'Email bestaat al.'], 400);
        }

        $validated['invite_token'] = Str::random(40);

        $user = User::create($validated);

        Mail::to($user->email)->send(new InviteUser($user));

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $docenten)
    {
        $docenten->update($request->validated());
        return new UserResource($docenten);
    }

    public function destroy(User $docenten)
    {
        $docenten->delete();
    }

    public function reinvite(User $docenten)
    {
        $docenten->invite_token = Str::random(40);
        $docenten->save();
        Mail::to($docenten->email)->send(new InviteUser($docenten));
    }

    public function changePassword()
    {

    }
}

