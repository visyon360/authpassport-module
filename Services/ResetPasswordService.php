<?php

namespace Modules\AuthPassport\Services;

use Illuminate\Support\Facades\Password;

class ResetPasswordService
{
    public $request;

    public $status;

    public $response;

    const RESPONSES = [
        Password::RESET_LINK_SENT => [
            'message' => 'link sent successfully',
            'code'    => 200,
        ],
        Password::PASSWORD_RESET  => [
            'message' => 'password reset successfully',
            'code'    => 200,
        ],
        Password::INVALID_TOKEN   => [
            'message' => 'invalid token',
            'code'    => 401,
        ],
        Password::RESET_THROTTLED => [
            'message' => 'throttled reset attempt',
            'code'    => 429,
        ],
        Password::INVALID_USER    => [
            'message' => 'invalid user',
            'code'    => 401,
        ],
    ];

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function makeReset()
    {
        $this->reset();
        $this->setResponse($this->status);

        return $this;
    }

    public function makeForgot()
    {
        $this->forgot();
        $this->setResponse($this->status);

        return $this;
    }

    public function forgot()
    {
        $this->status = Password::sendResetLink($this->request->only('email'));
        return $this;
    }

    public function reset()
    {
        $input = $this->request->only('email', 'password', 'password_confirmation', 'token');
        $this->status = Password::reset($input, function ($user, $password) {
            $user->forceFill(['password' => $password]);
            $user->save();
        });

        return $this;
    }

    public function setResponse($status)
    {
        $this->response = self::RESPONSES[$status];
        return $this;
    }
}
