<?php

namespace Modules\AuthPassport\Tests\Feature;

use App\Mail\ForgotPasswordMailable;
use App\Models\Locale;
use Modules\AuthPassport\Services\ResetPasswordService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use Modules\Users\Entities\User;
use Tests\TestCase;

class PasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['password' => '1234']);

        $response = $this->json('POST', route('api.passport.forgot-password'), [
            'email' => $user->email,
        ])->assertOk();

        $this->assertEquals(
            $response->decodeResponseJson()['message'],
            ResetPasswordService::RESPONSES[Password::RESET_LINK_SENT]['message']
        );
    }

    public function test_reset_password_success()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['password' => '1234']);
        $token = Password::broker()->createToken($user);

        $response = $this->json('POST', route('api.passport.reset-password'), [
            'email'                 => $user->email,
            'password'              => 'secreto123',
            'password_confirmation' => 'secreto123',
            'token'                 => $token,
        ])->assertOk();

        $this->assertEquals(
            $response->decodeResponseJson()['message'],
            ResetPasswordService::RESPONSES[Password::PASSWORD_RESET]['message']
        );

        $response_login = $this->json('POST', route('api.passport.login'), [
            'email'    => $user->email,
            'password' => 'secreto123',
        ]);

        $response_login->assertJsonStructure(['data' => ['token', 'user']]);
    }

    public function test_reset_password_fails_to_unauthorized()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create(['password' => '1234']);
        $token = Password::broker()->createToken($user);

        $response = $this->json('POST', route('api.passport.reset-password'), [
            'email'                 => $user->email,
            'password'              => 'secreto123',
            'password_confirmation' => 'secreto123',
            'token'                 => $token.'asd',
        ])->assertStatus(401);

        $this->assertEquals(
            $response->decodeResponseJson()['message'],
            ResetPasswordService::RESPONSES[Password::INVALID_TOKEN]['message']
        );
    }

    public function test_reset_password_mailable_content()
    {
        $user = User::factory()->create();
        $locale = Locale::create(['name' => 'English', 'iso' => 'es']);
        $user->update(['locale_id' => $locale->id]);

        (new ForgotPasswordMailable('token123'))->locale($user->locale->iso)->assertSeeInHtml('Resetear password');
        (new ForgotPasswordMailable('token123'))->locale('en')->assertSeeInHtml('Reset password');
    }
}
