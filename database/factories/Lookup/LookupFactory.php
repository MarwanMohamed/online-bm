<?php

namespace Database\Factories\Lookup;

use App\Models\Lookup\Lookup;
use App\Models\Lookup\LookupCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LookupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lookup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'code' => $this->faker->word,
            'value' => $this->faker->word,
            'model_type' => User::class,
            'category_id' => LookupCategory::factory(),
            'is_active' => $this->faker->boolean,
            'is_system' => $this->faker->boolean,
        ];
    }
}
