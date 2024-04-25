<?php

namespace Tests\Feature;

use App\Models\Lookup\Lookup;
use App\Models\Lookup\LookupCategory;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\LookupsSeeder;
use Tests\Feature\Structure\Lookups;
use Tests\Feature\Structure\LookupCategories;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LookupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LookupsSeeder::class);
    }

    /** @test */
    public function only_authenticated_user_can_list_lookup_categories()
    {
        $this->getJson('/api/lookups/list')->assertUnauthorized(); // 401

        $this->be(User::factory()->create());

        $this->getJson('/api/lookups/list')
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => ['*' => LookupCategories::structure()]
            ]);
    }

    /** @test */
    public function only_authenticated_user_can_list_lookups()
    {
        $this->getJson('/api/lookups/list')->assertUnauthorized(); // 401

        $this->be(User::factory()->create());

        $this->getJson('/api/lookups/list')
            ->assertSuccessful()
            ->assertJsonStructure([
                'data' => ['*' => Lookups::structure()]
            ])
            ->assertJsonPath('data.0.created_at.date', now()->format('d-m-Y'));
    }

    /** @test */
    public function only_authenticated_user_can_list_lookups_for_datatables()
    {
        $this->getJson('/api/lookups')->assertUnauthorized(); // 401

        $this->be(User::factory()->create());

        $this->getJson('/api/lookups')
            ->assertSuccessful()
            ->assertJsonStructure([
                'draw',
                'recordsTotal',
                'recordsFiltered',
                'data' => ['*' => Lookups::structure()]
            ])
            ->assertJsonPath('data.0.created_at.date', now()->format('d-m-Y'));
    }

    /** @test */
    public function it_can_filter_lookups_by_category_code()
    {
        $this->be(User::factory()->create());

        $data = $this->getJson('/api/lookups/list?category_code=customer_types')->json('data');

        $this->assertNotEmpty($data);

        $data = $this->getJson('/api/lookups/list?category_code=invalid')->json('data');

        $this->assertEmpty($data);
    }

    public function test_can_show_lookup()
    {
        $this->getJson('/api/lookups/1')->assertUnauthorized();

        $this->be(User::factory()->create());
        $lookup = Lookup::factory()->create(['code' => 'test lookups']);
        $response = $this->getJson('/api/lookups/' . $lookup->id)->assertSuccessful();
        $this->assertEquals('test lookups', $response->json()['data']['code']);
    }

    public function test_validation_on_update_lookup()
    {
        $this->be(User::factory()->create());

        $lookup = Lookup::factory()->create(['code' => 'test lookups']);

        $this->putJson('/api/lookups/' . $lookup->id)
            ->assertUnprocessable();
    }

    public function test_update_lookup()
    {
        $this->be(User::factory()->create());
        $lookup = Lookup::factory()->create(['code' => 'test lookups']);

        $this->putJson('/api/lookups/' . $lookup->id, [
            'code' => 'updated code',
            'category_id' => $lookup->category_id,
            'name' => $lookup->name,
            'model_type' => $lookup->model_type
        ])->assertOk();

        $this->assertDatabaseHas('lookups', ['id' => $lookup->id, 'code' => 'updated code']);
    }


    public function test_validation_on_store_lookup()
    {
        $this->be(User::factory()->create());
        $this->postJson('/api/lookups/')->assertUnprocessable();
    }

    public function test_store_lookup()
    {
        $this->be(User::factory()->create());
        $this->postJson('/api/lookups', [
            'code' => 'new Lookup',
            'name' => 'new Lookup name',
            'category_id' => LookupCategory::factory()->create()->id,
            'model_type' => Lookup::class
        ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'new Lookup name')
            ->assertJsonPath('data.code', 'new Lookup');
    }
}
