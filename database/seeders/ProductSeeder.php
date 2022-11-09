<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Gentcmen\Models\Product::factory()->count(10)->create()->each(function ($product) {
            $product->reviews()->createMany(\Gentcmen\Models\Review::factory()->count(2)->make()->toArray());
        });;
    }
}
