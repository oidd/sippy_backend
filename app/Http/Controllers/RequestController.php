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

        if (!Gate::allows('send-request', Point::findOrFail($inp['point_id'])))
            return response()->json('unauthorized', 401);

        //Gate::authorize('sendRequest', Point::findOrFail($inp['point_id']));

        return \App\Models\Request::create([
            'point_id' => $inp['point_id'],
            'user_id' => $request->user()->id,
        ]);
    }

    public function approveRequest($id, Request $request)
    {
        $req = \App\Models\Request::findOrFail($id);

        Gate::authorize('decideRequest', $req);

        if ($req->decision !== null)
            return response()->json('Request already decided as rejected.', 400);

        $req->approve = true;
        $req->decision = Date::now();

        $req->save();

        return $req;
    }

    public function declineRequest($id, Request $request)
    {
        $req = \App\Models\Request::findOrFail($id);

        Gate::authorize('decideRequest', $req);

        $req->approve = false;
        $req->decision = Date::now();

        $req->save();

        return $req;
    }

    public function showMyRequests(Request $request)
    {
        return $request->user()->requests()->get();
    }

    public function showRequestsForMe(Request $request)
    {
        return \App\Models\Request::where('point_id', $request->user()->point()->get()[0]->id)->get();
    }
}
