<?php

namespace Database\Factories;

use App\Models\Image;
use App\Models\ImageHash;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image_hash_id' => function () {
                return ImageHash::factory()->id;
            },
            'basename'      => $this->faker->firstNameFemale,
            'ext'           => 'jpg',
            't_ext'         => 'jpg',
            'original'      => $this->faker->lastName,
            'delkey'        => $this->faker->word,
            'mimetype'      => $this->faker->mimeType,
            'size'          => rand(1, 1024),
            'comment'       => $this->faker->words,
            'ip'            => $this->faker->ipv4,
        ];
    }
}
