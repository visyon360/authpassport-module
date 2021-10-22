<?php

namespace Modules\AuthPassport\Tests\Feature;

use App\Mail\UserRegisteredMailable;
use App\Models\Locale;
use App\Models\RegistrationToken;
use Modules\AuthPassport\Services\RegistrationTokenStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Users\Entities\User;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $this->withoutExceptionHandling();
        $response = $this->json('POST', route('api.passport.register'), [
            'email'       => 'test@test.dev',
            'password'    => '12345678',
            'langIsoCode' => 'es',
            'district'    => 'island',
        ])->assertOk();

        $response->assertJsonStructure(['status', 'message', 'data' => ['user']]);
        $this->assertTrue(User::find($response->decodeResponseJson()['data']['user']['id'])->hasRole('user'));
    }

    public function test_confirm_register_mailable_content()
    {
        $user = User::factory()->create();
        $locale = Locale::create(['name' => 'English', 'iso' => 'es']);
        $user->update(['locale_id' => $locale->id]);

        (new UserRegisteredMailable('token123'))->locale($user->locale->iso)->assertSeeInHtml('Confirmar email');
        (new UserRegisteredMailable('token123'))->locale('en')->assertSeeInHtml('Confirm email');
    }

    public function test_failed_confirmation_sign_up_with_expired_token()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $registration_token = RegistrationToken::factory()->create([
            'user_id'    => $user->id,
            'created_at' => now()->subHours(49),
        ]);

        $this->json('GET', route('api.passport.confirm'), [
            'token' => $registration_token->token,
        ])->assertStatus(401);
    }

    public function test_failed_confirmation_sign_up_with_non_valid_token()
    {
        $user = User::factory()->create();
        $registration_token = RegistrationToken::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->json('GET', route('api.passport.confirm'), [
            'token' => $registration_token->token . 'fail',
        ])->assertStatus(422);
    }

    public function test_successful_confirmation_sign_up_with_non_expired_token()
    {
        $user = User::factory()->create();
        $registration_token = RegistrationToken::factory()->create(['user_id' => $user->id]);

        $this->assertNull($registration_token->verified_at);
        $response = $this->json('GET', route('api.passport.confirm'), [
            'token' => $registration_token->token,
        ])->assertStatus(200);

        $this->assertNotNull(RegistrationToken::find($registration_token->id)->verified_at);
        $response->assertJsonStructure(['message', 'data' => ['token']]);

        $this->assertTrue(
            $response->decodeResponseJson()['message']
            === RegistrationTokenStatusService::VALID_TOKEN
        );
    }

    public function test_failed_confirmation_sign_up_with_already_confirmed_account()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        RegistrationToken::factory()->create([
            'user_id'    => $user->id,
            'created_at' => now()->subDays(3),
        ]);
        RegistrationToken::factory()->create([
            'user_id'    => $user->id,
            'created_at' => now()->subDays(4),
        ]);
        $registration_token = RegistrationToken::factory()->create([
            'user_id'     => $user->id,
            'verified_at' => now()->subDays(1),
        ]);

        $this->assertNotNull($registration_token->verified_at);
        $response = $this->json('GET', route('api.passport.confirm'), [
            'token' => $registration_token->token,
        ])->assertStatus(401);

        $this->assertTrue(
            $response->decodeResponseJson()['message']
            === RegistrationTokenStatusService::ACCOUNT_ALREADY_CONFIRMED
        );
    }
}
