<?php

namespace App\Http\Controllers;

use App\Actions\LoginAction;
use App\Actions\LogoutAction;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    /**
     * @throws AuthenticationException
     */
    public function login(LoginRequest $request, LoginAction $action)
    {
        $authenticated = $action($request->validated());

        return new LoginResource($authenticated);
    }

    public function logout(Request $request, LogoutAction $action)
    {
        $action($request->user());

        return response()->noContent();
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}
