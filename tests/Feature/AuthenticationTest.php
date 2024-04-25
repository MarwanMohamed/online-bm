<?php

namespace Tests\Feature;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\Feature\Structure\Users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // For Permission Categories
        $this->seed('LookupsSeeder');

        // For Permission & Roles
        $this->seed('RolesSeeder');
    }

    public function test_login()
    {
        $user = User::factory()->create(['email' => 'user@test.com', 'is_active' => false]);

        $this->assertGuest('web');

        $this->postJson('/api/auth/login', [
            'email' => 'user@test.com',
            'password' => 'invalid-password',
        ])->assertUnauthorized();

        $this->assertGuest('web');

        $this->postJson('/api/auth/login', [
            'email' => 'user@test.com',
            'password' => 'password',
        ])->assertUnauthorized(); // Inactive user

        $user->markAsActive();

        $this->postJson('/api/auth/login', [
            'email' => 'user@test.com',
            'password' => 'password',
        ])->assertSuccessful();

        $this->assertAuthenticated('web');
    }

    public function test_logout()
    {
        $this->be(User::factory()->create());

        $this->assertAuthenticated('web');

        $this->postJson('/api/auth/logout')->assertSuccessful();

        $this->assertGuest('web');
    }

    public function test_creating_testing_token()
    {
        User::factory()->create(['email' => 'user@test.com']);

        $this->postJson('/api/auth/create-testing-token', [
            'email' => 'user@test.com',
            'password' => 'password',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['access_token']]);
    }

    /** @test */
    public function the_authenticated_user_can_display_his_profile()
    {
        $this->getJson('/api/users/me')->assertUnauthorized();

        Sanctum::actingAs($user = User::factory()->create());

        $this->getJson('/api/users/me')
            ->assertSuccessful()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonStructure(['data' => Users::structure()]);
    }

    /** @test */
    public function the_authenticated_user_can_change_his_password()
    {
        $this->postJson('/api/auth/change-password')->assertUnauthorized();

        Sanctum::actingAs($user = User::factory()->create());

        $this->assertTrue(Hash::check('password', $user->password));

        // Test password
        $this->postJson('/api/auth/change-password', [
            'old_password' => 'password',
            'new_password' => $newPassword = Str::random(7),
            'new_password_confirmation' => $newPassword,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrorFor('new_password');

        $this->postJson('/api/auth/change-password', [
            'old_password' => 'password',
            'new_password' => $newPassword = Str::random(rand(8, 15)),
            'new_password_confirmation' => $newPassword,
        ])->assertSuccessful();

        $user->refresh();

        $this->assertFalse(Hash::check('password', $user->password));

        $this->assertTrue(Hash::check($newPassword, $user->password));
    }

    public function test_it_can_send_password_reset_verification_code_to_user()
    {
        Notification::fake();

        $this->postJson('api/auth/forgot-password')
            ->assertJsonValidationErrors(['email']);

        Notification::assertNothingSent();

        $user = User::factory()->create(['email' => 'user@test.com']);

        $this->postJson('api/auth/forgot-password', [
            'email' => 'user@test.com',
        ])
            ->assertSuccessful()
            ->assertSee(__('Kindly check your email inbox to complete the reset password steps'));

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }

    public function test_password_can_be_updated_by_reset_token()
    {
        $user = User::factory()->create(['email' => 'user@test.com']);
        $token = app('auth.password.broker')->createToken($user);

        $this->postJson('api/auth/reset-password')
            ->assertJsonValidationErrors(['password']);

        $this->postJson('api/auth/reset-password', [
            'token' => 'invalid',
            'email' => 'user@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertSee(__('passwords.token'));


        $this->postJson('api/auth/reset-password', [
            'token' => $token,
            'email' => 'user@test.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ])
            ->assertSuccessful()
            ->assertSee(__('passwords.reset'));

        // Now test login with new password.
        $this->postJson('api/auth/login', [
            'email' => $user->email,
            'password' => '12345678',
        ])->assertSuccessful();
    }
}
