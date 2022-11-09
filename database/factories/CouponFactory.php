<?php


namespace Database\Factories;


use Gentcmen\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Coupon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'cost' => $this->faker->randomDigit,
            'discount' => $this->faker->randomDigit,
            'expires_at' => $this->faker->dateTime(),
        ];
    }
}
