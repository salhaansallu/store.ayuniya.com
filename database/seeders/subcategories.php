<?php

namespace Database\Seeders;

use App\Models\SubCategories as ModelsSubCategories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class subcategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 5; $i++) {
            ModelsSubCategories::create([
                'sub_category_name' => "Sub category ".$i,
                'category_id' => rand(1, 3),
            ]);
        }
    }
}
