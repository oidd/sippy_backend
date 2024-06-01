<?php

namespace Database\Factories;

use App\Models\Call;
use App\Models\Point;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CallFactory extends Factory
{
    protected $model = Call::class;

    public function definition(): array
    {
        return [
            'decision' => $this->faker->randomNumber(),
            'approve' => $this->faker->boolean(),
            'user_id' => User::factory(),
            'point_id' => Point::factory(),
        ];
    }
}
