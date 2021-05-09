<?php

namespace Database\Factories;

use App\Models\DenyWord;
use Illuminate\Database\Eloquent\Factories\Factory;

class DenyWordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DenyWord::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'word' => $this->faker->word
        ];
    }
}
