<?php

namespace App\Http\Controllers;

use App\Models\Chunk;
use App\Models\Point;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    public function store(Request $request)
    {
        $inp = $request->validate(
            [
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'is_house' => 'required|boolean',
            ]
        );

        $point = Point::create([
            'geom' => DB::select("SELECT ST_SetSRID(ST_MakePoint(?, ?), 4326)", [$inp['latitude'], $inp['longitude']])[0]->st_setsrid,
            'is_house' => $inp['is_house'],
            'chunk_id' => Chunk::getChunkByCoordinates($inp['latitude'], $inp['longitude']),
            'user_id' => $request->user()->id,
        ]);

        return $point;
    }

    public function show(int $id, Request $request)
    {
        return Point::findOrFail($id);
    }

    public function getNearPoints(Request $request)
    {
        $inp = $request->validate(
            [
                'latitude' => 'required|string',
                'longitude' => 'required|string',
            ]
        );

        $curChunk = Chunk::getChunkByCoordinates($inp['longitude'], $inp['latitude']);

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
            $nearestChunksIds[] = DB::select('SELECT id
            FROM chunks
            WHERE ST_Intersects(ST_Translate(ST_SetSRID(ST_MakePoint(?, ?), 4326), ?, ?), chunks.geom)
            ', [$inp['longitude'], $inp['latitude'], $transition[0], $transition[1]])[0]->id;
        }

        $points = [];

        foreach ($nearestChunksIds as $nearestChunkId) {
            try {
                $points = array_merge($points, Chunk::findOrFail($nearestChunkId)->points()->get()->toArray());
            } catch (ModelNotFoundException $exception)
            {
                continue;
            }

        }

        return response()->json($points);
    }
}
