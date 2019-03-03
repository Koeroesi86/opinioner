<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('upload');
        $createdAt = time();

        $response = [
            'upload' => $file
        ];

        \Event::fire('BeforeAddFileVersion', $response);

        $extension = \File::extension($file->getClientOriginalName());
        $directory = str_replace('\\', '/', storage_path()) . '/attachments/' . $extension;
        $filename = $createdAt . "." . md5($file->getClientOriginalName()) . ".{$extension}";

        $response['success'] = $file->move($directory, $filename);
        if ($response['success']) {
            $response['storage_path'] = $directory . '/' . $filename;
        }

        \Event::fire('AfterAddFileVersion', $response);

        $response['message'] = "File added.";

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
