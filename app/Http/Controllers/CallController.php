<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CallController extends Controller
{
    public function sendCall(Point $point, Request $request)
    {
        Gate::authorize('send', [Call::class, $point]);

        if ($request->user()->calls()->get()->contains($point))
            throw new \Exception('You are not allowed to send a request to point twice', 403);

        return response()->json(
            Call::create([
            'point_id' => $point->id,
            'user_id' => $request->user()->id,
            ])
        );
    }

    public function unsendCall(Point $point, Request $request)
    {
        Gate::authorize('send', [Call::class, $point]);
        if (($c = $request->user()->calls()->where('point_id', $point->id))->exists())
            return response()->json($c->delete());

        throw new \Exception('Apparently there is no Call related to this Point from this user', 400);
    }

    public function approveCall(Call $call)
    {
        Gate::authorize('decide', $call);

        if ($call->decision !== null)
            throw new \Exception('Call already decided as rejected.');

        $call->approve = true;
        $call->decision = time();

        $call->save();

        Call::where('user_id', $call->user_id)->where('id', '!=', $call->id)->each(function ($call) {
            $call->delete();
        });
        // maybe should put it into a job (or at least put into separate service like I did with PointService)

        return response()->json($call);
    }

    public function declineCall(Call $call, Request $request)
    {
        Gate::authorize('decide', $call);

        $call->approve = false;
        $call->decision = time();

        $call->save();

        return response()->json($call);
    }

    public function showMyCalls(Request $request)
    {
        return response()->json($request->user()->calls()->get());
    }

    public function showCallsForMe(Request $request)
    {
        $p = $request->user()->point()->first();

        if ($p == null)
            return response()->json([]);

        return response()->json(Call::where('point_id', $request->user()->point()->id)->get());
    }
}
