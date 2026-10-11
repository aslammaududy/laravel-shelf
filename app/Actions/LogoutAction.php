<?php

namespace App\Actions;

use App\Models\User;

class LogoutAction
{
    public function __invoke(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
