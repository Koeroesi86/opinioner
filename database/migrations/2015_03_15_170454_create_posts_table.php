<?php

use App\Models\Post;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('internal_name', 200);
            $table->string('title', 300);
            $table->string('description', 500);
            $table->string('keywords', 250);
            $table->text('excerpt');
            $table->string('classname', 500);
            $table->mediumText('body');
            $table->string('uri', 200);
            $table->string('post_type', 60);
            $table->string('mime_type', 30);
            $table->integer('owner_id');
            $table->enum('position', array('head', 'body'));
            $table->integer('access_level');
            $table->timestamp('created_at');
        });

        $post = new Post;
        $post->internal_name = 'Homepage';
        $post->title = 'Homepage';
        $post->excerpt = '';
        $post->body = '';
        $post->post_type = 'homepage';
        $post->mime_type = '';
        $post->position = '';
        $post->uri = '/';
        $post->created_at = date('Y-m-d H:i:s', time());
        $post->owner_id = 1;
        $post->classname = '';
        $post->access_level = 0;
        $post->description = '';
        $post->keywords = '';

        $post->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }

}
