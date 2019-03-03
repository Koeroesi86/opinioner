<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Relation;
use Auth;
use Cache;
use Illuminate\Support\ServiceProvider;

class DataServiceProvider extends ServiceProvider
{
    private static $access_level = 0;
    private static $cacheTime = 5; //cache intercal in minutes
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $user = Auth::user();

        if($user && isset($user->access_level)) {
            self::$access_level = $user->access_level;
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public static function getPostFromURI($uri)
    {
        $access_level = self::$access_level;

        return Cache::remember('getFromURI-' . md5($uri) . '-' . $access_level, self::$cacheTime, function() use ($uri, $access_level) {
            return Post::getFromURI($uri, $access_level);
        });
    }

    public static function getLatestPosts($page = 0, $offset = 10) {
        $access_level = self::$access_level;

        return Cache::remember('getLatestPosts-page' . $page . '-' . $access_level, self::$cacheTime, function () use ($page, $offset, $access_level) {
            return Post::getLatestPosts($page, $offset, $access_level);
        });
    }

    public static function getRelatedPosts($uri, $isParent = false, $page = 0, $offset = 10) {
        $access_level = self::$access_level;

        return Cache::remember('getRelatedPosts-' . md5($uri) . '-' . $access_level . ($isParent ? '-page' . $page : '-np'), self::$cacheTime, function() use ($uri, $access_level, $page, $offset, $isParent) {
                return Relation::getRelatedPosts($uri, $access_level, $isParent, $page, $offset);
            });
    }

    public static function getLatestPostsCount() {
        $access_level = self::$access_level;

        return Cache::remember('getLatestPostsCount-' . $access_level, self::$cacheTime, function () use ($access_level) {
            return Post::getLatestPostsCount($access_level);
        });
    }

    public static function getPostParents($uri) {
        $access_level = self::$access_level;

        return Cache::remember('getParents-' . md5($uri) . '-' . $access_level, self::$cacheTime, function() use ($uri, $access_level) {
            return Relation::getParents($uri, $access_level);
        });
    }

    public static function getPostComments($uri) {
        $access_level = self::$access_level;

        return Cache::remember('getComments-' . md5($uri) . '-' . $access_level, self::$cacheTime, function() use ($uri, $access_level) {
            return Relation::getComments($uri, $access_level);
        });
    }

    public static function getSearch($search, $page = 0, $offset = 10) {
        $access_level = self::$access_level;

        return Cache::remember('getSearch-' . md5($search) . '-' . $access_level . 'page' . $page, self::$cacheTime, function () use ($search, $access_level, $offset, $page) {
            return Post::getSearch($search, $page, $offset, $access_level);
        });
    }

    public static function getSearchCount($search) {
        $access_level = self::$access_level;

        return Cache::remember('getSearchCount-' . md5($search) . '-' . $access_level, self::$cacheTime, function () use ($search, $access_level) {
            return Post::getSearchCount($search, $access_level);
        });
    }
}
