<?php


namespace Modules\AuthPassport\Traits;

use Illuminate\Support\Facades\Auth;

trait AuthUser
{

    /**
     * Get token authenticated user
     *
     * @return array
     */
    protected function getToken(): array
    {
        $user = Auth::user();
        $token = $user->createToken('Personal Access Token');
        $data = ['user' => $user];
        return [$data, $token];
    }
}
