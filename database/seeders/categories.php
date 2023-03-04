<?php

namespace Database\Seeders;

use App\Models\Categories as ModelsCategories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <= 3; $i++) {
            ModelsCategories::create([
                'category_name' => "Category ".$i,
            ]);
        }
    }
}
