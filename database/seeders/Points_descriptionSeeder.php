<?php

namespace Database\Seeders;

use App\Models\Points_description;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Points_descriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Points_description::factory()->count(5)->create();
    }
}
