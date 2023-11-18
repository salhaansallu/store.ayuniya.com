<?php

namespace Database\Seeders;

use App\Models\varients as ModelsVarients;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class varients extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($j=1; $j <= 27; $j++) { 
            for ($i = 1; $i <= 3; $i++) {
                ModelsVarients::create([
                    'sku' => 'sku'. rand(10000, 99999),
                    'v_name' => 'varient '.$i,
                    'unit' => 'pcs',
                    'qty' => rand(3,10),
                    'price' => $price = rand(1111, 9999),
                    'sales_price' => $price/1.2,
                    'weight' => rand(1, 2)/1.3,
                    'status' => 'active',
                    'image_path' => 'product-1.jpg',
                    'pro_id' => $j,
                ]);
            }
        }
    }
}
