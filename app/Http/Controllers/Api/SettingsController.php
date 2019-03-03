<?php

namespace App\Http\Controllers\Api;

use App\Models\Settings;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SettingsController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $query = DB::table('settings');
        $query->orderBy('name');

        $settings = $query->get();

        return response()->json($settings, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        $columns = (new Settings)->getFillable();
        return response()->json($columns, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function show($id, Request $request)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        $option = Settings::find($id);

        if (!($option instanceof Settings)) {
            return response('', 404);
        }
        return response()->json($option, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Settings $option
     * @return Response
     */
    public function edit(Settings $option)
    {
        if ($this->user instanceof User && $this->user->access_level <= 1) {
            return response('', 403);
        }

        $columns = $option->getFillable();
        return response()->json($columns, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Settings $option
     * @return Response
     */
    public function destroy(Settings $option)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        if ($option instanceof Settings) {
            $option->delete();
            return response('', 204);
        }

        return response('', 404);
    }
}
