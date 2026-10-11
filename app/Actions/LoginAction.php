<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;

class LoginAction
{
    // DUMMY_HASH is needed to hide the consuming time for hash checking the password
    // because checking the hash consume time much longer, so the email check can be guessed
    // one by one for the right email.
    //
    private const DUMMY_HASH = '$2y$12$wV0CaK96oPdemrZoyKKCGeZgfp5WwSoScnPeBJjLQCOjDmGfJVS.i';

    /**
     * @throws AuthenticationException
     */
    public function __invoke(array $credentials): NewAccessToken
    {
        $user = User::where('email', $credentials['email'])->first();

        // get the password hash for that user. if the user doesn't found, use the dummy hash.
        $passwordHash = $user?->password ?? self::DUMMY_HASH;

        // check the hash. so the consumed time is equal for both found and not found user
        if (! Hash::check($credentials['password'], $passwordHash) || ! $user) {
            throw new AuthenticationException('Invalid credentials.');
        }

        return $user->createToken('login', ['*'], now()->addDay());
    }
}
