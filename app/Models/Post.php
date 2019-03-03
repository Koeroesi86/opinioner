<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    public $timestamps = false;
    protected $fillable = [
        'internal_name',
        'title',
        'description',
        'keywords',
        'excerpt',
        'body',
        'uri',
        'post_type',
        'mime_type',
        'owner_id',
        'access_level',
        'created_at'
    ];

    //
    public static function getFromURI($uri, $access_level = 0)
    {
        $post = self::where(function ($query) use ($uri) {
            return $query
                ->where('uri', '/' . trim($uri, '/'))
                ->orWhere('uri', '/' . trim($uri, '/') . '/');
        })
            ->where('access_level', '<=', $access_level)
            ->orderBy('created_at', 'desc')
            ->first();

        return $post;
    }

    public static function getLatestPosts($page = 0, $offset = 10, $access_level = 0)
    {
        $list = self::where('post_type', 'post')
            ->where('access_level', '<=', $access_level)
            ->orderBy('created_at', 'desc')
            ->groupBy('uri')
            ->skip($page * $offset)
            ->take($offset)
            ->get();

        $latestPosts = array();
        foreach ($list as $item) {
            $a = self::getFromURI($item->uri, $access_level);
            if (!isset($a->uri)) continue;
            //$a->position = $item->position;
            $a->relatedPosts = Relation::getRelatedPosts($item->uri);
            $a->parentPosts = Relation::getParents($item->uri);

            $a->featuredImage = '';
            foreach ($a->relatedPosts as $rel_post) {
                if ($rel_post->post_type == 'attachment' && $rel_post->position == 'featuredImage') {
                    $a->featuredImage = $rel_post->uri;
                    break;
                }
            }
            if (count($a) > 0) $latestPosts[] = $a;
        }

        return $latestPosts;
    }

    public static function getLatestPostsCount($access_level = 0)
    {
        return self::where('post_type', 'post')
            ->where('access_level', '<=', $access_level)
            //->orderBy('created_at', 'desc')
            ->distinct('uri')
            ->count('uri');
    }

    public static function getSearchCount($search, $access_level = 0)
    {
        $count = self::where(function ($query) use ($search) {
            return $query
                ->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('excerpt', 'LIKE', '%' . $search . '%')
                ->orWhere('body', 'LIKE', '%' . $search . '%')
                ->orWhere('uri', 'LIKE', '%' . $search . '%');
        })
            ->where('access_level', '<=', $access_level)
            ->where('post_type', '<>', 'attachment')
            //->orderBy('created_at', 'desc')
            //->groupBy('uri')
            ->distinct('uri')
            ->count('uri');


        return $count;
    }

    public static function getSearch($search, $page = 0, $offset = 10, $access_level = 0)
    {
        $list = self::where(function ($query) use ($search) {
            return $query
                ->where('title', 'LIKE', '%' . $search . '%')
                ->orWhere('excerpt', 'LIKE', '%' . $search . '%')
                ->orWhere('body', 'LIKE', '%' . $search . '%')
                ->orWhere('uri', 'LIKE', '%' . $search . '%');
        })
            ->where('access_level', '<=', $access_level)
            ->where('post_type', '<>', 'attachment')
            ->orderBy('created_at', 'desc')
            ->groupBy('uri')
            ->skip($page * $offset)
            ->take($offset)
            ->get();

        $latestPosts = array();
        foreach ($list as $item) {
            $a = Post::getFromURI($item->uri, $access_level);
            //$a->position = $item->position;
            $a->relatedPosts = Relation::getRelatedPosts($item->uri, $access_level);
            $a->parentPosts = Relation::getParents($item->uri, $access_level);

            $a->featuredImage = '';
            foreach ($a->relatedPosts as $rel_post) {
                if ($rel_post->post_type == 'attachment' && $rel_post->position == 'featuredImage') {
                    $a->featuredImage = $rel_post->uri;
                    break;
                }
            }
            if (count($a) > 0) $latestPosts[] = $a;
        }

        return $latestPosts;
    }

    public function author()
    {
        return $this->belongsTo(User::class , 'owner_id');
    }

    public function relatedPosts()
    {
        return $this->hasMany(Relation::class);
    }

    public function parents()
    {
        return $this->belongsTo(Relation::class);
    }

//    public function comments()
//    {
//        return DataServiceProvider::getPostComments($this->uri);
//    }
//
//    public function relatedPosts($p, $off)
//    {
//        return DataServiceProvider::getRelatedPosts($this->uri, ($this->post_type == 'post-category'), $p, $off);
//    }
//
//    public function parentPosts($p, $off)
//    {
//        return DataServiceProvider::getPostParents($this->uri);
//    }

}
