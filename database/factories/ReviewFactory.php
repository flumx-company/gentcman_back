<?php
namespace Database\Factories;

use Gentcmen\Models\Product;
use Gentcmen\Models\Review;
use Gentcmen\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'review' => $this->faker->paragraph,
            'rating' => $this->faker->numberBetween(0, 5),
            'user_id' => function() {
                return User::inRandomOrder()->first();
            },
            'product_id' => function() {
                return Product::inRandomOrder()->first();
            },
        ];
    }
}
