<?php

namespace Modules\AuthPassport\Tests\Feature\API;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Modules\Users\Entities\User;
use Tests\TestCase;

class AuthPassportTestCase extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
        Artisan::call('db:seed', ['--class' => 'Modules\ACL\Database\Seeders\LaratrustSeeder']);
    }

    /**
     * Create and sign up a user
     *
     * @param  string  $role
     * @param  array  $userData
     *
     * @return Collection|User
     */
    public function createAndSignInUser(string $role, array $userData = [])
    {
        $user = User::factory()->create($userData);

        $user->attachRole($role);

        Passport::actingAs($user);

        return $user;
    }
}
