<?php

namespace Database\Factories;

use App\Models\Point;
use App\Models\Points_description;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class Points_descriptionFactory extends Factory
{
    protected $model = Points_description::class;

    public function definition(): array
    {
        return [
            'preferable_gender' => $this->faker->boolean(),
            'starts_at' => $this->faker->randomNumber(),
            'min_preferable_age' => ($num=fake()->numberBetween(18, 40)),
            'max_preferable_age' => $num + 10,
            'name' => fake()->text(10),
            'description' => fake()->text(100),
            'point_id' => Point::factory(),
//            'point_id' => fake()->unique()->randomElement(Point::all()->keys()->toArray()),
        ];
    }
}
