<?php

namespace App\Http\Controllers;

use App\Models\Chunk;
use App\Models\Point;
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
        $point = Point::findOrFail($id);

        return response()->json(
            [
                'longitude' => DB::select('select ST_X(?)', [$point->geom])[0]->st_x,
                'latitude' => DB::select('select ST_Y(?)', [$point->geom])[0]->st_y,
                'is_house' => $point->is_house,
                'chunk_id' => $point->chunk_id,
                'user_id' => $point->user_id,
                'id' => $point->id,
            ]
        );
    }
}
