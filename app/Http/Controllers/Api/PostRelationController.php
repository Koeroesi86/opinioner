<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Relation;
use DB;
use Illuminate\Http\Request;
use Input;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class PostRelationController
 * @package App\Http\Controllers\Api
 */
class PostRelationController extends ApiBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        $position = $request->get('position');
        $uri_a = $request->get('uri_a');
        $uri_b = $request->get('uri_b');
        $orderBy = $request->get('orderBy');

        $query = DB::table('post_to_post');

        if ($uri_a !== null) {
            if (!empty($uri_a)) {
                $query->where('uri_a', $uri_a);
            } else {
                $query->where('uri_a', '=', '')->orWhereNull('uri_a');
            }
        }

        if ($uri_b !== null) {
            if (!empty($uri_b)) {
                $query->where('uri_b', $uri_b);
            } else {
                $query->where('uri_b', '=', '')->orWhereNull('uri_b');
            }
        }

        if ($position !== null) {
            $query->where('position', $position);
        }

        if ($orderBy !== null) {
            $query->orderBy($orderBy);
        } else {
            $query->orderBy('id');
        }

        $relatedPosts = $query->get();

        //TODO: maybe a nicer way?
        foreach ($relatedPosts as $relatedPost) {
            $relatedPost->post = Post::getFromURI($relatedPost->uri_a);
        }

        return response()->json($relatedPosts, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        $columns = (new Relation)->getFillable();
        return response()->json($columns, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        /** @var ParameterBag $data */
        $data = $request->json();
        $formFields = $data->get('formFields');
        $response = [
            'formFields' => $formFields
        ];

        $t = time();

        $id = DB::table('post_to_post')->insertGetId([
            'uri_a' => $formFields['uri_a'],
            'uri_b' => $formFields['uri_b'],
            'position' => $formFields['position'],
            'created_at' => (new \DateTime())->setTimestamp($t)->format("Y-m-d H:i:s")
        ]);

        $response['relation'] = Relation::find($id);

        $response['message'] = "Post relation added.";

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $relation = '';
        $uri = $request->get('uri');
        if (is_numeric($id)) {
            $relation = Relation::find($id);
        } elseif ($uri) { //lazy load with uri
//            $post = Relation::getFromURI($uri, $this->access_level);
        }

        if (!($relation instanceof Relation)) {
            return response('', 404);
        }
        return response()->json($relation, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Relation $relation
     * @return \Illuminate\Http\Response
     */
    public function edit(Relation $relation)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        return response()->json($relation->getFillable(), 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        //TODO
        $relation = Relation::find($id);
        if ($relation instanceof Relation) {
            $relation->update();
            return response()->json($relation, 200, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        $relation = Relation::find($id);
        if ($relation instanceof Relation) {
            $relation->delete();
            return response('', 204);
        }

        return response('', 404);
    }
}
