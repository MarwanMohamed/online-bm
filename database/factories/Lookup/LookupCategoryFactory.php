<?php

namespace Database\Factories\Lookup;

use App\Models\Lookup\Lookup;
use App\Models\Lookup\LookupCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LookupCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LookupCategory::class;

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
        ];
    }
}
