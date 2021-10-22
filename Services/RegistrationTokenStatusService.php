<?php

namespace Modules\AuthPassport\Services;

use App\Models\RegistrationToken;
use Carbon\Carbon;

class RegistrationTokenStatusService
{
    public $token_hash;

    public $user;

    public $token;

    const ACCOUNT_ALREADY_CONFIRMED = 'account_already_verified';
    const NOT_VALID_TOKEN = 'not_valid_token';
    const VALID_TOKEN = 'valid_token';

    public function __construct($token_hash)
    {
        $this->token_hash = $token_hash;
    }

    public function make(): RegistrationTokenStatusService
    {
        $this->setToken();
        return $this;
    }

    public function setToken() : void
    {
        $this->token = RegistrationToken::whereToken($this->token_hash)->first();
    }

    public function getAccountStatusMessage(): string
    {
        if ($this->checkUserHasAlreadyConfirmedAccount()) {
            return self::ACCOUNT_ALREADY_CONFIRMED;
        }

        if (!$this->userHasAValidToken()) {
            return self::NOT_VALID_TOKEN;
        }

        return self::VALID_TOKEN;
    }

    public function getStatusCode($token_status): int
    {
        return $token_status === self::VALID_TOKEN ? 200 : 401;
    }

    /**
     * @return boolean
     */
    public function checkUserHasAlreadyConfirmedAccount(): bool
    {
        $user = $this->token->user;
        return $user->registrationTokens()
            ->whereNotNull('verified_at')
            ->exists();
    }

    public function userHasAValidToken(): bool
    {
        $registration_token = RegistrationToken::whereToken($this->token_hash)
            ->where('created_at', '>=', Carbon::now()->subHours(48))
            ->whereNull('verified_at')
            ->orderBy('id', 'DESC')
            ->first();

        return ! is_null($registration_token);
    }
}
