<?php


namespace Database\Factories;


use Gentcmen\Models\BonusPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class BonusPointFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BonusPoint::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'points' => $this->faker->randomFloat(null, 100, 500),
        ];
    }
}
