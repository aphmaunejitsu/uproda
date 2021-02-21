<?php

namespace Database\Factories;

use App\Models\ImageHash;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageHashFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ImageHash::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'hash'    => md5($this->faker->word),
            'comment' => $this->faker->words,
            'ng'      => rand(0, 1)
        ];
    }
}
