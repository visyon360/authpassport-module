<?php

namespace Modules\AuthPassport\Tests\Feature\API;

use Modules\Users\Entities\User;

class LoginApiControllerTest extends AuthPassportTestCase
{
    const API_OAUTH_LOGIN = 'api.oauth.login';

    public function test_a_user_can_login()
    {
        $user = User::factory()->create([
            'email'    => 'samle@test.com',
            'password' => 'sample123',
        ]);

        $loginData = ['email' => $user->email, 'password' => 'sample123'];
        $this->postJson(route(self::API_OAUTH_LOGIN), $loginData)
          ->assertStatus(200)
          ->assertJsonStructure([
              'status',
              'message',
              'data' => [
                  'access_token',
                  'token_type',
                  'expires_at',
                  'user',
              ],
          ]);


        $this->assertAuthenticated();
    }

    public function test_a_user_can_not_authenticated_with_invalid_credentials()
    {
        $loginData = ['email' => 'sample@test.com', 'password' => 'sample1232'];
        $this->postJson(route(self::API_OAUTH_LOGIN), $loginData)
          ->assertStatus(401)
          ->assertJsonFragment([
              'status'  => 401,
              'message' => __('auth.failed'),
              'errors'  => [],
          ]);
    }

    public function test_a_user_login_must_enter_email_and_password()
    {
        $this->withoutExceptionHandling();
        $this->postJson(route(self::API_OAUTH_LOGIN))
          ->assertStatus(402)
          ->assertJson([
              "status"  => 402,
              "message" => "The given data was invalid.",
              "errors"  => [
                  'email'    => ["The email field is required."],
                  'password' => ["The password field is required."],
              ],
          ]);
    }

    public function test_a_user_login_must_enter_email()
    {
        $this->postJson(route(self::API_OAUTH_LOGIN), ['password' => 'password'])
          ->assertStatus(402)
          ->assertJson([
              "status"  => 402,
              "message" => "The given data was invalid.",
              "errors"  => [
                  'email' => ["The email field is required."],
              ],
          ]);
    }

    public function test_a_user_login_must_enter_passport()
    {
        $this->postJson(route(self::API_OAUTH_LOGIN), ['email' => 'email@test.com'])
          ->assertStatus(402)
          ->assertJson([
              "status"  => 402,
              "message" => "The given data was invalid.",
              "errors"  => [
                  'password' => ["The password field is required."],
              ],
          ]);
    }
}
