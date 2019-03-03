<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model {
	protected $table = 'post_to_post';
	protected $fillable = [
							'uri_a',
							'uri_b',
							'position',
							'created_at'
						];

    public static function getRelatedPosts($uri, $access_level = 0, $isParent = false, $page = 0, $offset = 10) {
        $list = [];
        if(!$isParent) {
            $list = self::where(function ($query) use ($uri) {
                if (!empty($uri) && $uri !== "/")
                    return $query
                        ->where('uri_b', trim($uri, '/'))
                        ->orWhere('uri_b', '/' . trim($uri, '/'))
                        ->orWhere('uri_b', '/' . trim($uri, '/') . '/');
                else
                    return $query
                        ->where('uri_b', $uri);
            })
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                //->groupBy('uri_a')
                ->get();
        } else {
            $list = self::where('uri_b', 'LIKE', '/' . trim($uri,'/') . '/%')
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->skip($page * $offset)
                ->take($offset)
                ->get();
        }
		
		$relatedPosts = array();
		foreach($list as $item) {
			 $a = Post::getFromURI($item->uri_a, $access_level);
			 
			 if(count($a) == 0 || !isset($a->uri)) continue;
			 
			 $a->position = $item->position;
			 $a->relation_id = $item->id;
			 $a->relatedPosts = self::getRelatedPosts($item->uri_a, $access_level);
			 $a->parentPosts = self::getParents($item->uri_a, $access_level);
			 $relatedPosts[] = $a;
		}
		
		return $relatedPosts;
	}

	public static function getParents($uri, $access_level = 0) {
		$list = self::where(function($query) use ($uri) {
                    return $query
                        ->where('uri_a', '/' . ltrim($uri,'/'))
                        ->orWhere('uri_a', '/' . ltrim($uri,'/') . '/');
                })
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

		$parentPosts = array();
		foreach($list as $item) {
			 $a = Post::getFromURI($item->uri_b, $access_level);
			 
			 if(count($a) == 0) continue;
			 
			 $a->position = $item->position;
			 $a->parents = Relation::getParents($item->uri_b, $access_level);
			 $parentPosts[] = $a;
		}
		
		return $parentPosts;
	}

	public static function getComments($uri, $access_level) {
            $comments = self::leftJoin('posts', function($join) {
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

            foreach ($comments as $i => $comment) {
                /*$comment->author = DB::table('users')
                                    ->where('users.id', $comment->owner_id)
                                    ->first();*/
                $comment->author = User::find($comment->owner_id);
            }

            return $comments;
	}
}
