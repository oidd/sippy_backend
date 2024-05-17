<?php

namespace Database\Factories;

use App\Models\Chunk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Query\Grammars\PostgresGrammar;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Point>
 */
class PointFactory extends Factory
{
    public function geometryFromPoint(float $a, float $b)
    {
        $sql = "SELECT ST_SetSRID(ST_MakePoint(?, ?), 4326) AS geom_point";
        return DB::select($sql, [$a, $b])[0]->geom_point;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        while (true) {
            try {
                $x = rand(50, 80);
                $y = rand(25, 170);

                $ch = Chunk::getChunkByCoordinates($x, $y);
            } catch (\Exception $e) {
                continue;
            }
            break;
        }

        return [
            'geom' => $this->geometryFromPoint($x, $y),
            'is_house' => $this->faker->boolean(),
            'user_id' => $this->faker->randomDigit()+1,
            'chunk_id' => Chunk::getChunkByCoordinates($x, $y),
        ];
    }
}
