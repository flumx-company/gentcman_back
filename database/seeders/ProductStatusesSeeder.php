<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Gentcmen\Models\ProductStatus::factory(5)->create();
    }
}
