<?php

namespace Modules\AuthPassport\Services;

use App\Models\RegistrationToken;

class ConfirmUserRegisterService
{
    protected $request;

    public $registrationToken;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function make()
    {
        $this->registrationToken = $this->getRegistrationTokenByTokenHash($this->request->token);
        return $this;
    }

    public function getRegistrationTokenByTokenHash($token)
    {
        return RegistrationToken::whereToken($token)->first();
    }

    public function createUserBearerToken()
    {
        return createAccessToken($this->registrationToken->user)->accessToken;
    }

    public function setRegistrationTokenAsVerified()
    {
        $this->registrationToken->update(['verified_at' => now()]);
    }

    public function createBearerTokenAndSetAccountAsVerified()
    {
        $bearer = $this->createUserBearerToken();
        if ($bearer) {
            $this->setRegistrationTokenAsVerified();
        }

        return $bearer;
    }

    public function getAccessToken($token_status = false)
    {
        $bearer_token = null;
        if ($token_status === RegistrationTokenStatusService::VALID_TOKEN) {
            $bearer_token = $this->createBearerTokenAndSetAccountAsVerified();
        }

        return $bearer_token;
    }
}
