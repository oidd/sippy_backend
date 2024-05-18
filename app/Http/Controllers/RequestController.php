<?php

namespace App\Http\Controllers;


use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;

class RequestController extends Controller
{
    public function sendRequest(Request $request)
    {
        $inp = $request->validate(
            [
                'point_id' => 'required|integer',
            ]
        );

//        Gate::authorize('sendRequest', Point::findOrFail($inp['point_id']));

        return \App\Models\Request::create([
            'point_id' => $inp['point_id'],
            'user_id' => $request->user()->id,
        ]);
    }

    public function approveRequest($id, Request $request)
    {
        $req = \App\Models\Request::findOrFail($id);

        Gate::authorize('decideRequest', $req);

        if ($req->decided !== null)
            return response()->json('Request already decided', 400);

        $req->approve = true;
        $req->decided = Date::now();

        $req->save();

        return $req;
    }

    public function declineRequest($id, Request $request)
    {
        $req = \App\Models\Request::findOrFail($id);

        Gate::authorize('decideRequest', $req);

        $req->approve = false;
        $req->decided = Date::now();

        $req->save();

        return $req;
    }

    public function showMyRequests(Request $request)
    {
        return $request->user()->requests()->get();
    }

    public function showRequestsForMe(Request $request)
    {
        $reqs = [];

        foreach ($request->user()->points()->get() as $point) {
            $reqs[] = \App\Models\Request::where('point_id', $point->id)->get();
        }

        return $reqs;
    }
}
