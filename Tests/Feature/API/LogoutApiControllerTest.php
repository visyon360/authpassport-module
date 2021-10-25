<?php

namespace Modules\AuthPassport\Tests\Feature\API;

use Modules\Users\Entities\User;

class LogoutApiControllerTest extends AuthPassportTestCase
{

    public function test_a_user_can_log_out()
    {
        $user = User::factory()->create([
            'email'    => 'sample@test.com',
            'password' => 'sample123',
        ]);


        $response = $this->postJson(route('api.oauth.login'), [
            'email'    => $user->email,
            'password' => 'sample123',
        ])->assertStatus(200);


        $responseJSON = json_decode($response->getContent(), true);

        $token = $responseJSON['data']['access_token'];


        $this->postJson(route('api.oauth.logout'), [], ['Authorization' => 'Bearer '.$token])
             ->assertStatus(200);
    }
}
