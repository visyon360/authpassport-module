<?php

namespace Modules\AuthPassport\Services;

use App\Mail\UserRegisteredMailable;
use App\Models\District;
use App\Models\Locale;
use App\Models\RegistrationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Users\Entities\User;

class RegisterUserService
{
    public $user;

    public $registration_token;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function make()
    {
        $this->user = $this->createUser($this->request);
        $this->registration_token = $this->createRegistrationToken($this->user->id);
        $this->sendConfirmAccountEmail($this->user);
        $this->setDistrictBySlug($this->request->district);

        return $this;
    }

    public function createUser(Request $request)
    {
        return User::create([
            'email'     => $request->email,
            'password'  => $request->password,
            'locale_id' => Locale::whereIso($request->langIsoCode)->first()->id,
        ]);
    }

    public function setDistrictBySlug($slug)
    {
        $district = District::whereSlug($slug)->first();
        return $this->user->update(['district_id' => $district->id]);
    }

    public function assignUserRole($role = 'user')
    {
        $this->user->attachRole($role);
    }

    public function createRegistrationToken($user_id)
    {
        return RegistrationToken::create([
            'user_id' => $user_id,
            'token'   => md5(rand(1, 10) . microtime()),
        ]);
    }

    public function sendConfirmAccountEmail(User $user)
    {
        $token = $user->getLatestActiveRegistrationToken()->token;
        Mail::to($user)->send(new UserRegisteredMailable($token));
    }
}
