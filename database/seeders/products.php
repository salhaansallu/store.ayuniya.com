<?php

namespace Database\Seeders;

use App\Models\products as ModelsProducts;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class products extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 27; $i++) {
            ModelsProducts::create([
                'product_name' => $faker->name,
                'short_des' => $faker->sentence,
                'long_des' => $faker->sentence,
                'category' => rand(1,2),
                'banner' => 'product-1.jpg',
                'vendor' => rand(1,2),
            ]);
        }
    }
}
