<?php

namespace App\Http\Controllers;

use App\Http\Requests\Point\nearestRequest;
use App\Http\Requests\Point\storeRequest;
use App\Http\Requests\Point\updateRequest;
use App\Models\Point;
use App\Models\Points_description;
use App\Service\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PointController extends Controller
{
    public function store(storeRequest $request)
    {
        return response()->json(PointService::createPoint($request->validated()));
    }

    public function updatePoint(Point $point, updateRequest $request)
    {
        Gate::authorize('update', $point);

        return response()->json(PointService::updatePoint($point, $request->validated()));
    }

    public function updatePointDescription(Point $point, \App\Http\Requests\PointDescription\updateRequest $request)
    {
        Gate::authorize('update', $point);

        ($p = $point->description())->update($request->validated());

        return response()->json($p->get());
    }

    public function showPoint(Point $point)
    {
        Gate::authorize('read', $point);

        return response()->json($point);
    }

    public function showDescription(Point $point)
    {
        Gate::authorize('read', $point);

        return response()->json($point->description()->first());
    }

    public function showNearPoints(nearestRequest $request)
    {
        return response()->json(PointService::nearest($request->validated()));
    }

    public function showMyPoint(Request $request)
    {
        return response()->json($request->user()->point()->firstOrFail());
    }

    public function destroy(Point $point)
    {
        Gate::authorize('delete', $point);

        return response()->json($point->delete());
    }
}
