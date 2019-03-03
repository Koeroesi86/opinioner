<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class PostController
 * @package App\Http\Controllers\Api
 */
class PostController extends ApiBaseController
{
    /**
     * Display a listing of the posts
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }
        $uri = $request->get('uri');
        $groupBy = $request->get('groupBy');
        $type = $request->get('type');
        $orderBy = $request->get('orderBy');

        $query = DB::table('posts');
        if ($uri !== null) {
            $query->where('uri', '=', $uri);
        }

        if ($type !== null) {
            if (strpos($type, ',') != false) {
                $query->whereIn('post_type', explode(',', $type));
            } else {
                $query->where('post_type', '=', $type);
            }
        }

        if ($groupBy !== null) {
            $query->groupBy([$groupBy]);
        }

        if ($orderBy !== null) {
            $query->orderBy($orderBy);
        } else {
            $query->orderBy('title');
        }

        $posts = $query
            ->where('access_level', '<=', $this->user->access_level)
            ->get();

        return response()->json($posts, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the fields to create post
     *
     * @return Response
     */
    public function create()
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        $columns = (new Post)->getFillable();
        return response()->json($columns, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Store a newly created post
     *
     * @param Request $request
     * @return Response
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

        $createdAt = time();

        \Event::fire('BeforeAddPost', $response);

        $post = new Post;
        $post->internal_name = isset($formFields['internal_name']) ? $formFields['internal_name'] : '';
        $post->title = isset($formFields['title']) ? $formFields['title'] : '';
        $post->excerpt = isset($formFields['excerpt']) ? $formFields['excerpt'] : '';
        $post->body = isset($formFields['body']) ? $formFields['body'] : '';
        $post->post_type = $formFields['post_type'];
        $post->mime_type = isset($formFields['mime_type']) ? $formFields['mime_type'] : '';
        $post->position = isset($formFields['position']) ? $formFields['position'] : '';
        $post->uri = $formFields['uri'];
        $post->created_at = date('Y-m-d H:i:s', $createdAt);
        $post->owner_id = Auth::id();
        $post->classname = '';
        $post->access_level = $formFields['access_level'];
        $post->description = isset($formFields['description']) ? $formFields['description'] : '';
        $post->keywords = isset($formFields['keywords']) ? $formFields['keywords'] : '';

        $post->save();

//        $id = DB::table('posts')->insertGetId(
//            array(
//                'internal_name' => $formFields['internal_name'],
//                'title' => $formFields['title'],
//                'excerpt' => $formFields['excerpt'],
//                'body' => $formFields['body'],
//                'post_type' => $formFields['post_type'],
//                'uri' => $formFields['uri'],
//                'created_at' => date('Y-m-d H:i:s', $createdAt),
//                'owner_id' => Auth::id(),
//                'classname' => '',
//                'access_level' => $formFields['access_level'],
//                'description' => $formFields['description'],
//                'keywords' => $formFields['keywords'],
//            )
//        );

        $response['post'] = $post;

        \Event::fire('AfterAddPost', $response['post']);

        $response['message'] = "Post version added.";

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified post
     *
     * @param  int $id
     * @param Request $request
     * @return Response
     */
    public function show($id, Request $request)
    {
        $post = null;
        $uri = $request->get('uri');
        if (is_numeric($id)) {
            $post = Post::find($id);
            if ($post->access_level > $this->access_level) {
                return response('', 403);
            }
        } elseif ($uri) { //lazy load with uri
            $post = Post::getFromURI($uri, $this->access_level);
        }

        if (!($post instanceof Post)) {
            return response('', 404);
        }
        return response()->json($post, 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Show the fields to edit post
     *
     * @param  Post $post
     * @return Response
     */
    public function edit(Post $post)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        return response()->json($post->getFillable(), 200, [], JSON_PRETTY_PRINT);
    }

    /**
     * Update the specified post
     *
     * @param $id
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        //TODO: makes even any sense?
        echo '<pre>';
        var_dump($request);
        die;
        $post = Post::find($id);
        $post->update();
    }

    /**
     * Remove the specified post
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function destroy($id, Request $request)
    {
        if (!$this->checkAccessLevel(2)) {
            return response('', 403);
        }

        $uri = $request->get('uri');

        if (is_numeric($id)) {
            $post = Post::find($id);
            if ($post->access_level > $this->user->access_level) {
                return response('', 403);
            }
            $post->delete();
            return response('', 204);
        } elseif ($uri) {
            /** @var Post[] $posts */
            DB::table('posts')
                ->where('uri', '=', $uri)
                ->where('access_level', '<=', $this->user->access_level)
                ->delete();
            return response('', 204);
        }

        return response('', 404);
    }
}
