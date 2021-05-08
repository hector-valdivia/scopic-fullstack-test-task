<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'        => $this->faker->catchPhrase,
            'description' => $this->faker->text,
            'user_id'     => User::factory(),
            'ends_at'     => $this->faker->dateTimeBetween('this year', '+3 months'),
        ];
    }
}
