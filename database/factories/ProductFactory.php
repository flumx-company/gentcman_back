<?php

namespace Database\Factories;

use Gentcmen\Models\Product;
use Gentcmen\Models\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

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
            'amount' => $this->faker->randomDigit,
            'rating' => $this->faker->randomDigit,
            'description' => $this->faker->text,
            'meta_title' => $this->faker->text,
            'meta_description' => $this->faker->text,
            'meta_keywords' => $this->faker->text,
            'content' => ['size' => 5],
            'images_content' => ['size' => 5],
            'product_status_id' => ProductStatus::inRandomOrder()->first(),
        ];
    }
}
