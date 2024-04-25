<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Tests\Feature\Structure\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
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

    /** @test */
    public function only_authenticated_user_can_list_users_for_dropdowns()
    {
        $this->getJson('/api/users/list')->assertUnauthorized(); // 401

        $this->be(User::factory()->create());

        $this->getJson('/api/users/list')
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => ['*' => ['id', 'name', 'email', 'profile_picture', 'is_active']],
            ])
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_can_filter_users_list_by_name()
    {
        $this->be(User::factory()->create(['name' => 'Ahmed']));

        User::factory()->create(['name' => 'Mina']);

        $this->json('GET', '/api/users/list', ['name' => 'Mi'])
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Mina');
    }

    /** @test */
    public function it_can_filter_users_list_by_ids()
    {
        $this->be($user = User::factory()->create(['name' => 'Ahmed']));

        $user->givePermissionTo('view_user');

        $user2 = User::factory()->create(['name' => 'Mina']);

        $this->getJson('/api/users?selected_ids[]='.$user2->id)
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Mina');
    }

    /** @test */
    public function it_can_filter_users_list_by_active_status()
    {
        $this->be($user = User::factory()->create(['name' => 'Ahmed', 'is_active' => 1]));

        $user->givePermissionTo('view_user');

        User::factory()->create(['name' => 'Mina', 'is_active' => 0]);

        // Display only active users.
        foreach ([1, '1', 'true', 'on', 'yes'] as $true) {
            $this->getJson('/api/users?is_active='.$true)
                ->assertSuccessful()
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.name', 'Ahmed');
        }

        // Display only inactive users.
        foreach ([0, '0', 'false', 'off', 'no', 'any thing else...'] as $false) {
            $this->getJson('/api/users?is_active='.$false)
                ->assertSuccessful()
                ->assertJsonCount(1, 'data')
                ->assertJsonPath('data.0.name', 'Mina');
        }

        // Display active & inactive users.
        foreach ([null, ''] as $empty) {
            $this->getJson('/api/users?is_active='.$empty)
                ->assertSuccessful()
                ->assertJsonCount(2, 'data');
        }
    }

    /** @test */
    public function only_authenticated_user_with_permission_can_list_users_for_datatable()
    {
        $this->getJson('/api/users')->assertUnauthorized(); // 401

        $this->be($user = User::factory()->create());

        $this->getJson('/api/users')->assertForbidden();

        $user->givePermissionTo('view_user');

        $this->getJson('/api/users')
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => ['*' => Users::structure()],
            ])
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function the_last_added_user_should_be_on_the_top_of_the_users_list()
    {
        $this->be($user = User::factory()->create(['name' => 'Ahmed']));

        User::factory()->create(['name' => 'Marwan']);

        $this->json('GET', '/api/users')->assertForbidden();

        $user->givePermissionTo('view_user');

        $this->json('GET', '/api/users', [
            'columns' => [
                [
                    'name' => 'id',
                    'data' => 'id',
                    'searchable' => true,
                    'orderable' => true,
                ],
                [
                    'name' => 'name',
                    'data' => 'name',
                    'searchable' => true,
                    'orderable' => true,
                ],
                [
                    'name' => 'email',
                    'data' => 'email',
                    'searchable' => true,
                    'orderable' => true,
                ],
            ],
            'order' => [
                [
                    'column' => 'id',
                    'dir' => 'DESC',
                ],
            ],
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['*' => Users::structure()]])
            ->assertJsonPath('data.0.name', 'Marwan')
            ->assertJsonPath('data.1.name', 'Ahmed');
    }

    /** @test */
    public function the_user_with_permission_can_view_users()
    {
        $this->be($auth = User::factory()->create());

        $user = User::factory()->create(['name' => 'Marwan']);

        $this->getJson(sprintf('/api/users/%d', $user->id))->assertForbidden();

        $auth->givePermissionTo('view_user');

        $this->getJson(sprintf('/api/users/%d', $user->id))
            ->assertSuccessful()
            ->assertJsonStructure(['data' => Users::structure()])
            ->assertJsonPath('data.name', 'Marwan');
    }

    /** @test */
    public function the_user_with_permission_can_add_users()
    {
        Storage::fake();

        // Should be logged in.
        $this->postJson('/api/users')->assertUnauthorized();

        $this->be($user = User::factory()->create());

        // Should have permission.
        $this->postJson('/api/users')->assertForbidden();

        $user->givePermissionTo('add_user');

        // Should fill required data
        $this->postJson('/api/users', [
            'name' => '',
            'email' => '',
            'role_id' => '',
            'password' => '',
            'password_confirmation' => '',
        ])
            ->assertJsonValidationErrorFor('name')
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrorFor('role_id')
            ->assertJsonValidationErrorFor('password');

        // Should fill valid data
        $this->postJson('/api/users', [
            'name' => Str::random(101),
            'email' => 'invalid',
            'role_id' => 'invalid',
            'password' => '123',
            'password_confirmation' => '456',
        ])
            ->assertJsonValidationErrorFor('name')
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrorFor('role_id')
            ->assertJsonValidationErrorFor('password');

        $this->assertDatabaseMissing('users', ['name' => 'ahmed']);

        // Admin role added by seeder from setUp.
        $role = Role::first();

        $this->postJson('/api/users', [
            'name' => 'ahmed',
            'email' => 'ahmed@test.com',
            'role_id' => $role->id,
            'password' => 'Pass_1234',
            'password_confirmation' => 'Pass_1234',
            'profile_picture' => UploadedFile::fake()->image('picture.png'),
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'name' => 'ahmed',
            'email' => 'ahmed@test.com',
        ]);

        $this->assertDatabaseCount('users', 2);

        $this->assertTrue(
            ($createdUser = User::firstWhere('name', 'ahmed'))->hasRole('admin')
        );

        $this->assertTrue($createdUser->getFirstMedia('profile_picture')->file_name == 'picture.png');
    }

    public function test_unique_email_validation()
    {
        $user = User::factory()->create([
            'name' => 'Marwan',
            'email' => 'marwan@test.com',
        ]);

        $this->be($auth = User::factory()->create());

        $auth->givePermissionTo('edit_user');

        $this->postJson(sprintf('/api/users/%d', $auth->id), [
            '_method' => 'PUT',
            'email' => 'marwan@test.com',
        ])->assertJsonValidationErrorFor('email');

        $this->postJson(sprintf('/api/users/%d', $user->id), [
            '_method' => 'PUT',
            'email' => 'marwan@test.com',
        ])->assertJsonMissingValidationErrors('email');

        $user->delete();

        $this->postJson(sprintf('/api/users/%d', $auth->id), [
            '_method' => 'PUT',
            'email' => 'marwan@test.com',
        ])->assertJsonMissingValidationErrors('email');
    }

    /** @test */
    public function the_user_with_permission_can_edit_users()
    {
        Storage::fake();

        $user = User::factory()->create(['name' => 'Marwan']);

        // Should be logged in.
        $this->postJson(sprintf('/api/users/%d', $user->id), ['_method' => 'PUT'])->assertUnauthorized();

        $this->be($auth = User::factory()->create());

        // Should have permission.
        $this->postJson(sprintf('/api/users/%d', $user->id), ['_method' => 'PUT'])->assertForbidden();

        $auth->givePermissionTo('edit_user');

        // Should fill required data
        $this->postJson(sprintf('/api/users/%d', $user->id), [
            '_method' => 'PUT',
            'name' => '',
            'email' => '',
            'role_id' => '',
            'password' => '',
            'password_confirmation' => '',
        ])
            ->assertJsonValidationErrorFor('name')
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrorFor('role_id');

        // Should fill valid data
        $this->postJson(sprintf('/api/users/%d', $user->id), [
            '_method' => 'PUT',
            'name' => Str::random(101),
            'email' => 'invalid',
            'role_id' => 'invalid',
            'password' => 'Pass_1234',
            'password_confirmation' => 'Pass_1234',
        ])
            ->assertJsonValidationErrorFor('name')
            ->assertJsonValidationErrorFor('email')
            ->assertJsonValidationErrorFor('role_id');

        $this->assertDatabaseMissing('users', ['name' => 'ahmed']);
        $this->assertDatabaseHas('users', ['name' => 'Marwan']);

        // Admin role added by seeder from setUp.
        $role = Role::first();

        $this->postJson(sprintf('/api/users/%d', $user->id), [
            '_method' => 'PUT',
            'name' => 'ahmed',
            'email' => 'ahmed@test.com',
            'role_id' => $role->id,
            'password' => 'Pass_1234',
            'password_confirmation' => 'Pass_1234',
            'profile_picture' => UploadedFile::fake()->image('picture.png'),
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'name' => 'ahmed',
            'email' => 'ahmed@test.com',
        ]);

        $this->assertDatabaseCount('users', 2);

        $this->assertTrue(
            ($createdUser = User::firstWhere('name', 'ahmed'))->hasRole('admin')
        );

        $this->assertTrue($createdUser->getFirstMedia('profile_picture')->file_name == 'picture.png');
    }

    /** @test */
    public function the_user_with_permission_can_delete_users()
    {
        $user = User::factory()->create(['name' => 'Marwan']);

        $this->deleteJson(sprintf('/api/users/%d', $user->id))->assertUnauthorized();

        $this->be($auth = User::factory()->create());

        $this->deleteJson(sprintf('/api/users/%d', $user->id))->assertForbidden();

        $this->assertNotNull(User::query()->firstWhere('name', 'Marwan'));

        $auth->givePermissionTo('delete_user');

        $this->deleteJson(sprintf('/api/users/%d', $user->id))->assertSuccessful();

        $this->assertNull(User::query()->firstWhere('name', 'Marwan'));
    }

    /** @test */
    public function the_user_with_permission_can_inactive_users()
    {
        $user = User::factory()->create(['name' => 'Marwan']);

        $this->patchJson(sprintf('/api/users/%d/active', $user->id))->assertUnauthorized();

        $this->be($auth = User::factory()->create());

        $this->patchJson(sprintf('/api/users/%d/active', $user->id))->assertForbidden();

        $this->assertTrue(! ! $user->refresh()->is_active);

        $auth->givePermissionTo('active_user');

        $this->patchJson(sprintf('/api/users/%d/active', $user->id))->assertSuccessful();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_type' => User::class,
            'tokenable_id' => $user->id,
        ]);

        $this->assertFalse(! ! $user->refresh()->is_active);
    }

    /** @test */
    public function the_user_should_logged_out_if_marked_as_inactive()
    {
        $this->be($auth = User::factory()->create(), 'web');

        $this->assertAuthenticated('web');

        $this->assertAuthenticated('sanctum');

        $this->getJson('/api/users/me')->assertSuccessful();

        $auth->forceFill(['is_active' => false])->saveQuietly();

        $this->getJson('/api/users/me')->assertUnauthorized();

        $this->assertGuest('web');
    }

    /** @test */
    public function the_user_with_permission_can_active_users()
    {
        $user = User::factory()->create(['name' => 'Marwan', 'is_active' => false]);

        $this->patchJson(sprintf('/api/users/%d/active', $user->id))->assertUnauthorized();

        $this->be($auth = User::factory()->create());

        $this->patchJson(sprintf('/api/users/%d/active', $user->id))->assertForbidden();

        $this->assertFalse(! ! $user->refresh()->is_active);

        $auth->givePermissionTo('active_user');

        $this->patchJson(sprintf('/api/users/%d/active', $user->id))->assertSuccessful();

        $this->assertTrue(! ! $user->refresh()->is_active);
    }
}
