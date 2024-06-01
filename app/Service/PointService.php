<?php

namespace App\Service;

use App\Models\Chunk;
use App\Models\Point;
use App\Models\Points_description;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PointService
{
    public static function createPoint(array $inp)
    {
        DB::beginTransaction();

        try {
            $point = Point::create([
                'geom' => self::getGeomByCoordinates($inp['longitude'], $inp['latitude']),
                'category_id' => $inp['category_id'],
                'chunk_id' => Chunk::getChunkByCoordinates($inp['longitude'], $inp['latitude']),
                'user_id' => Auth::user()->id,
            ]);

            $description = Points_description::create([
                'point_id' => $point->id,
                'preferable_gender' => $inp['preferable_gender'] ?? null,
                'starts_at' => $inp['starts_at'] ?? null,
                'max_preferable_age' => $inp['max_preferable_age'] ?? null,
                'min_preferable_age' => $inp['min_preferable_age'] ?? null,
                'description' => $inp['description'] ?? null,
                'name' => $inp['name'],
            ]);

            DB::commit();

            return $point;
        } catch (\Exception $exception)
        {
            DB::rollBack();
            throw $exception;
        }
    }

    public static function updatePoint(Point $p, array $inp)
    {
        if (isset($inp['longitude']) || isset($inp['latitude']))
        {
            $inp['geom'] = self::getGeomByCoordinates($inp['longitude'] ?? $p->longitude, $inp['latitude'] ?? $p->latitude);
            unset($inp['latitude'], $inp['longitude']);
        }

        $p->update($inp);

        $p->chunk_id = Chunk::getChunkByCoordinates($p->longitude, $p->latitude);

        $p->save();

        return $p;
    }

    public static function nearest($inp)
    {
        $transitions = [
            [0, 0],
            [0.45,  0],
            [0, 0.45],
            [-0.45,  0],
            [0, -0.45],
            [0.45, 0.45],
            [0.45, -0.45],
            [-0.45, 0.45],
            [-0.45, -0.45],
        ];

        foreach ($transitions as $transition) {
            $nearestChunksIds[] = DB::select('SELECT id FROM chunks WHERE ST_Intersects(ST_Translate(ST_SetSRID(ST_MakePoint(?, ?), 4326), ?, ?), chunks.geom)', [$inp['longitude'], $inp['latitude'], $transition[0], $transition[1]])[0]->id;
        }

        $points = [];

        foreach ($nearestChunksIds as $nearestChunkId) {
            try {
//                $points = array_merge($points, Chunk::findOrFail($nearestChunkId)->points()->get()->toArray());
                foreach (Chunk::findOrFail($nearestChunkId)->points()->get() as $point) {
                    if ($point->shouldShowToUser(Auth::user()))
                        $points[] = $point;
                }
            } catch (ModelNotFoundException $exception)
            {
                continue;
            }

        }

        return $points;
    }

    public static function getGeomByCoordinates($longitude, $latitude)
    {
        return DB::select('SELECT ST_SetSRID(ST_MakePoint(?, ?), 4326)', [$longitude, $latitude])[0]->st_setsrid;
    }
}
