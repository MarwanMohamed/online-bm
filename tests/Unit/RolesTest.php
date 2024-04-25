<?php

namespace Tests\Unit;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RolesTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_roles()
    {
        $this->getJson('/api/roles')->assertUnauthorized();

        $this->be(User::factory()->create());

        $this->getJson('api/roles')
            ->assertSuccessful()
            ->assertJsonCount(0, 'data');

        Role::create(['name' => 'test_role', 'display_name' => 'test role']);

        $this->getJson('api/roles')
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => ['*' => ['name', 'display_name']]
            ])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.display_name', 'test role');
    }
}
