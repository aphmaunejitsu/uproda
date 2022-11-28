<?php

namespace Database\Factories;

use App\Models\DenyIp;
use Illuminate\Database\Eloquent\Factories\Factory;

class DenyIpFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DenyIp::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ip'     => $this->faker->ipv4,
            'is_tor' => rand(0, 1)
        ];
    }
}
