<?php

namespace App\Http\Controllers;


use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (\App\Models\Request::find(['point_id' => $request['point_id'], 'user_id' => $request->user()->id]) !== null)
            return response()->json(['message' => 'You are not allowed to send a request to point twice', 'error' => [
                'bad request'
            ]], 400);

        if (!Gate::allows('send-request', Point::findOrFail($inp['point_id'])))
            return response()->json(['message' => 'You are not allowed to send a request to this point', 'error' => [
                'bad login'
            ]], 400);

        return \App\Models\Request::create([
            'point_id' => $inp['point_id'],
            'user_id' => $request->user()->id,
        ]);
    }

    public function approveRequest($id, Request $request)
    {
        $req = \App\Models\Request::findOrFail($id);

        if (!Gate::allows('decide-request', $req))
            return response()->json(['message' => 'You are not allowed to approve or decline a request to this point', 'error' => 'bad login'], 400);

        if ($req->decision !== null)
            return response()->json('Request already decided as rejected.', 400);

        $req->approve = true;
        $req->decision = time();

        $req->save();

        $reqs = \App\Models\Request::where('id', $req->id)->get();

//        foreach ($reqs as $req) {
//            $req->approve = false;
//            $
//        }

        return $req;
    }

    public function declineRequest($id, Request $request)
    {
        $req = \App\Models\Request::findOrFail($id);

        if (!Gate::allows('decide-request', $req))
            return response()->json(['message' => 'You are not allowed to approve or decline a request to this point', 'error' => 'bad login'], 400);

        $req->approve = false;
        $req->decision = time();

        $req->save();

        return $req;
    }

    public function showMyRequests(Request $request)
    {
        return $request->user()->requests()->get();
    }

    public function showRequestsForMe(Request $request)
    {
        $p = $request->user()->point()->first();

        if ($p == null)
            return response()->json([]);

        return \App\Models\Request::where('point_id', $request->user()->point()->get()->id)->get();
    }

    public function showMyPoint(Request $request)
    {
        return $request->user()->point()->first();
    }
}
