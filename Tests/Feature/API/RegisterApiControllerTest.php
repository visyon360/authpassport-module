<?php

namespace Modules\AuthPassport\Tests\Feature\API;

use Modules\Users\Entities\User;

class RegisterApiControllerTest extends AuthPassportTestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->make();
    }

    public function test_a_user_can_register()
    {
        $this->postJson(
            route('api.oauth.register'),
            [
                'name'                  => $this->user->name,
                'lastname'              => $this->user->lastname,
                'email'                 => $this->user->email,
                'password'              => 'password',
                'password_confirmation' => 'password',
            ]
        )->assertStatus(200)
             ->assertJsonStructure([
                 'status',
                 'message',
                 'data' => [
                     'access_token',
                     'token_type',
                     'expires_at',
                     'user' => [],
                 ],
             ]);
    }


    public function test_a_register_with_validation_error()
    {
        $this->postJson(
            route('api.oauth.register'),
            [
                'email'                 => $this->user->email,
                'password'              => 'password',
                'password_confirmation' => 'password',
            ]
        )->assertStatus(402)
             ->assertJsonStructure([
                 'message',
                 'errors',
             ]);
    }
}
