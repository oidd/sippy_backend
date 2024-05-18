<?php

namespace App\Http\Controllers;

use App\Models\Chunk;
use App\Models\Point;
use App\Models\Points_description;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PointController extends Controller
{
    public function store(Request $request)
    {
        $inp = $request->validate(
            [
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'is_house' => 'required|boolean',
                'preferable_gender' => 'boolean',
                'starts_at' => 'date',
                'min_preferable_age' => 'integer|between:16,100',
                'max_preferable_age' => 'integer|between:16,100',
            ]
        );

        DB::beginTransaction();

        try {
            $point = Point::create([
                'geom' => DB::select("SELECT ST_SetSRID(ST_MakePoint(?, ?), 4326)", [$inp['latitude'], $inp['longitude']])[0]->st_setsrid,
                'is_house' => $inp['is_house'],
                'chunk_id' => Chunk::getChunkByCoordinates($inp['latitude'], $inp['longitude']),
                'user_id' => $request->user()->id,
            ]);

            $description = Points_description::create([
                'point_id' => $point->id,
                'preferable_gender' => $inp['preferable_gender'] ?? null,
                'starts_at' => $inp['starts_at'] ?? null,
                'max_preferable_age' => $inp['max_preferable_age'] ?? null,
                'min_preferable_age' => $inp['min_preferable_age'] ?? null,
            ]);

            DB::commit();

            return response()->json($point);
        } catch (\Exception $exception)
        {
            DB::rollBack();
            throw $exception;
        }
    }

    public function updatePoint(int $id, Request $request)
    {
        $inp = $request->validate(
            [
                'latitude' => 'nullable|string',
                'longitude' => 'nullable|string',
                'is_house' => 'nullable|boolean',
            ]
        );

        Gate::authorize('update', $p = Point::findOrFail($id));

        if (isset($inp['longitude']) || isset($inp['latitude']))
        {
            $inp['geom'] = DB::select("SELECT ST_SetSRID(ST_MakePoint(?, ?), 4326)", [$inp['latitude'] ?? $p->latitude, $inp['longitude'] ?? $p->longitude])[0]->st_setsrid;
            unset($inp['latitude'], $inp['longitude']);
        }

        Point::where('id', $id)->update($inp);
        $p = Point::find($id);

        $p->chunk_id = Chunk::getChunkByCoordinates($p->longitude, $p->latitude);

        $p->save();

        return response()->json($p);
    }

    public function updatePointDescription(int $id, Request $request)
    {
        $inp = $request->validate(
            [
                'preferable_gender' => 'nullable|boolean',
                'starts_at' => 'nullable|date',
                'max_preferable_age' => 'nullable|integer|between:16,100',
                'min_preferable_age' => 'nullable|integer|between:16,100',
            ]
        );

        Gate::authorize('update', Point::find($id));

        ($p = Points_description::where('point_id', $id))->update($inp);
        return response()->json($p->get());
    }

    public function showPoint(int $id, Request $request)
    {
        Gate::authorize('read', Point::findOrFail($id));

        return Point::findOrFail($id);
    }

    public function showDescription(int $id, Request $request)
    {
//        dd([$request->user(), Point::findOrFail($id)->with('description')->get()]);

        Gate::authorize('read', Point::findOrFail($id));

        return Points_description::where('point_id', $id)->first();
    }

    public function showNearPoints(Request $request)
    {
        $inp = $request->validate(
            [
                'latitude' => 'required|string',
                'longitude' => 'required|string',
            ]
        );

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
//                $points = array_merge($points, Chunk::findOrFail($nearestChunkId)->points()->get()->toArray());
                foreach (Chunk::findOrFail($nearestChunkId)->points()->get() as $point) {
                    if ($point->shouldShowToUser($request->user()))
                        $points[] = $point;
                }
            } catch (ModelNotFoundException $exception)
            {
                continue;
            }

        }

        return response()->json($points);
    }

    public function destroy($id, Request $request)
    {
        Gate::authorize('delete', Point::findOrFail($id));

        return Point::destroy($id);
    }
}
