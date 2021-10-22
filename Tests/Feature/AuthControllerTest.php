<?php

namespace Modules\AuthPassport\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Users\Entities\User;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_successful()
    {
        $user = User::factory()->create(['password' => '1234']);
        $response = $this->json('POST', route('api.passport.login'), [
            'email'    => $user->email,
            'password' => '1234',
        ]);

        $response->assertJsonStructure(['data' => ['token', 'user']]);
    }

    public function test_login_unauthorized_access()
    {
        $user = User::factory()->create(['password' => 'qwerty']);
        $response = $this->json('POST', route('api.passport.login'), [
            'email'    => $user->email,
            'password' => '1234',
        ])->assertStatus(401);

        $this->assertEquals($response->decodeResponseJson()['message'], 'Unauthorized access');
    }

    public function test_get_auth_user()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['password' => '1234']);
        $response = $this->json('POST', route('api.passport.login'), [
            'email'    => $user->email,
            'password' => '1234',
        ]);

        $response = $this->json(
            'GET',
            route('api.passport.user'),
            [],
            ['Authorization' => 'Bearer ' . $response->decodeResponseJson()['data']['token']]
        )
            ->assertStatus(200);

        $response->assertJsonStructure(['data' => ['user']]);
    }

    public function test_logout_user()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['password' => '1234']);
        $response_login = $this->json('POST', route('api.passport.login'), [
            'email'    => $user->email,
            'password' => '1234',
        ])->assertStatus(200);

        $logout_response = $this->json(
            'GET',
            route('api.passport.logout'),
            [],
            ['Authorization' => 'Bearer ' . $response_login->decodeResponseJson()['data']['token']]
        )->assertStatus(200);

        $this->assertTrue($user->tokens->first()->revoked);
    }
}
