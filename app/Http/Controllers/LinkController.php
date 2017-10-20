<?php

namespace App\Http\Controllers;

use Validator;
use App\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $links = Link::where(['user_id' => Auth::user()->id])->get();
        return response()->json([
            'links' => $links,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'url|active_url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 500);
        }

        do {
            $newHash = Str::random(6);
        } while (Link::where('hash', '=', $newHash)->count() > 0);

        $link = Link::create([
            'url' => request('url'),
            'hash' => $newHash,
            'user_id' => Auth::user()->id
        ]);

        return response()->json([
            'link' => $link,
            'message' => 'Success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Link $link
     * @return \Illuminate\Http\Response
     */
    public function show(Link $link)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Link $link
     * @return \Illuminate\Http\Response
     */
    public function edit(Link $link)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Link $link
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Link $link)
    {
        $this->validate($request, [
            'url' => 'required|max:255'
        ]);

        do {
            $newHash = Str::random(6);
        } while (Link::where('hash', '=', $newHash)->count() > 0);

        $link->url = request('url');
        $link->hash = $newHash;
        $link->save();

        return response()->json([
            'message' => 'Link updated successfully!'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Link $link
     * @return \Illuminate\Http\Response
     */
    public function destroy(Link $link)
    {
        $link->delete();
        return response()->json([
            'message' => 'Link delete successfully!'
        ], 200);
    }
}
