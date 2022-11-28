<?php

namespace Database\Factories;

use App\Models\ChunkFile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

class ChunkFileFactory extends Factory
{
    use WithFaker;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChunkFile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'uuid'        => $this->faker->uuid,
            'is_uploaded' => $this->faker->boolean,
            'is_fail'     => $this->faker->boolean,
        ];
    }
}
