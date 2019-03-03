<?php namespace App\Handlers\Events;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

use Input;
use DB;
use Auth;

class Page {
	private $user;
	private $access_level = 0;

    /**
     * Create the event handler.
     *
     */
	public function __construct()
	{
		$this->user = Auth::user();

		if($this->user && isset($this->user->access_level)) {
			$this->access_level = $this->user->access_level;
		}
	}

	/**
     * Handle AJAX requests.
     */
    public function onBeforeAJAXResponse($data)
    {
        $data->req = (object)Input::all();

        if($this->user && isset($this->user->access_level) && $this->user->access_level > 0) {
			if(isset($data->req->handler) && $data->req->handler == "Page") {
			$data->message = "User permitted.";
				$method = $data->req->type;
				if(method_exists($this, $method)) {
					$this->$method($data);
				}
				else {
					$data->message = "Method not exists: " . $method;
				}
			}
        }
        else {
        	$data->message = "You are not permitted.";
        }

        return $data;
    }

    private function AddComment($data) {
    	/*
    	$rules = array(
		    'uri' => 'unique:posts' //only for import purpose...
		);

		$validator = Validator::make((array)$post, $rules);

		if ($validator->passes()) {
		*/

		    $access_level = $this->access_level;

		    $data->req->formFields = (object)$data->req->formFields;

		    $t = time();
		    $uri = '/comment/' . $t;
		    $comment = $data->req->formFields->comment;

		    DB::table('posts')->insert(
			    array(
			    	'internal_name' => 'Comment',
			    	'title' => 'Comment',
					'excerpt' => '',
					'body' => $comment,
					'post_type' => 'comment',
					'uri' => $uri,
					'created_at' => date('Y-m-d H:i:s', $t),
					'owner_id' => Auth::id()
			    )
			);

			DB::table('post_to_post')->insert(
			    array(
			    	'uri_a' => $uri,
			    	'uri_b' => '/' . ltrim($data->req->formFields->post_uri,'/'),
					'position' => 'comment',
					'created_at' => date('Y-m-d H:i:s', $t)
			    )
			);

			$data->comments =
					DB::table('post_to_post')
									->leftJoin('posts', function($join) {
										return $join->on('post_to_post.uri_a', '=', 'posts.uri');
									})
									->where('post_to_post.position', 'comment')
									->where('posts.post_type', 'comment')
									->where('posts.access_level', '<=', $access_level)
									->where(function($query) use($uri) {
										return $query
												->where('post_to_post.uri_b', trim($uri,'/'))
												->orWhere('post_to_post.uri_b', '/' . trim($uri,'/'))
												->orWhere('post_to_post.uri_b', '/' . trim($uri,'/') . '/');
									})
									->orderBy('posts.created_at', 'desc')
									->get();
									//->leftJoin('users', 'users.id', '=', 'posts.owner_id')

			foreach ($data->comments as $i => $comment) {
				# code...
				$comment->author = DB::table('users')
									->where('users.id', $comment->owner_id);
			}
		/*}*/

    	$data->message = "Comment sent.";
    }
}
