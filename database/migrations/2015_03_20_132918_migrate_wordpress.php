<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateWordpress extends Migration {
	private $wpPath = '/var/www/f1info.hu/'; //location where wp-load.php found
	private $wpURL = ''; //url base what is used by WP, loaded from config
	private $categories = array();

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//uncomment if needed
		//$this->migrateWP();
	}

	private function migrateWP() {
		define('WP_USE_THEMES', false);
		require_once($this->wpPath . 'wp-load.php');

		$this->wpURL = rtrim(get_site_url(), "/") . "/";
		
		echo "Custom pages importing:";
		$this->addCustom();
		echo " done.\n";
		
		echo "Categories importing:";
		$this->importCategories();
		echo " done.\n";
		
		echo "Menus importing:";
		$this->importMenus();
		echo " done.\n";

		echo "Users importing:";
		$this->importUsers();
		echo " done.\n";

		echo "Copying uploads:";
		$this->copyUploads();
		echo " done.\n";
		
		echo "Posts, pages and images importing:";
		$success = $this->importPosts();
		echo " done.\n";

		//$success = File::copyDirectory($this->wpPath . 'wp-content/uploads/', '/var/www/f1info.hu.new/storage/app/attachments/wp-content/uploads/');
		if($success) {
			echo "Successfull import.\n";
		}
		else {
			echo "Couldn't copy uploads.\n";
		}
	}
	
	private function addCustom() {
		$post = new \stdClass;
		$post->title = 'Főoldal';
		$post->excerpt = '';
		$post->body = "";
		$post->created_at = '';
		$post->post_type = 'homepage';
		$post->position = '';
		$post->uri = '/';
		
		$post->id = $this->addPost($post)->id;

		$post = new \stdClass;
		$post->title = 'Hirdetés';
		$post->excerpt = '';
		$post->body = "<script async src=\"//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js\"></script>
<!-- F1infó oldalsáv -->
<ins class=\"adsbygoogle\" style=\"display:block;width:300px;height:250px;margin:0 auto;\" data-ad-client=\"ca-pub-3724457553630974\" data-ad-slot=\"7176372982\"></ins>
<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>";
		$post->created_at = '';
		$post->post_type = 'widget';
		$post->position = 'sidebar-right';
		$post->uri = '/sidebar-right/advertisement';
		
		$post->id = $this->addPost($post)->id;
		
		DB::table('post_to_post')->insertGetId(
			array(
				'uri_a' => $post->uri,
				'uri_b' => '',
				'position' => $post->position,
				'created_at' => $post->created_at
			)
		);

		$post = new \stdClass;
		$post->title = 'Következő futam';
		$post->excerpt = '';
		$post->body = "[msr_next_race('F1','2015')]";
		$post->created_at = '';
		$post->post_type = 'widget';
		$post->position = 'sidebar-right';
		$post->uri = '/sidebar-right/next-race';
		
		$post->id = $this->addPost($post)->id;
		
		DB::table('post_to_post')->insertGetId(
			array(
				'uri_a' => $post->uri,
				'uri_b' => '',
				'position' => $post->position,
				'created_at' => $post->created_at
			)
		);
		
		$post = new \stdClass;
		$post->title = 'Egyéni bajnokság';
		$post->excerpt = '';
		$post->body = "[msr_drivers_champ('F1','2015')]";
		$post->created_at = '';
		$post->post_type = 'widget';
		$post->position = 'sidebar-right';
		$post->uri = '/sidebar-right/drivers-champ';
		
		$post->id = $this->addPost($post)->id;
		
		DB::table('post_to_post')->insertGetId(
			array(
				'uri_a' => $post->uri,
				'uri_b' => '',
				'position' => $post->position,
				'created_at' => $post->created_at
			)
		);
		
		$post = new \stdClass;
		$post->title = 'Konstruktőri bajnokság';
		$post->excerpt = '';
		$post->body = "[msr_teams_champ('F1','2015')]";
		$post->created_at = '';
		$post->post_type = 'widget';
		$post->position = 'sidebar-right';
		$post->uri = '/sidebar-right/teams-champ';
		
		$post->id = $this->addPost($post)->id;
		
		DB::table('post_to_post')->insertGetId(
			array(
				'uri_a' => $post->uri,
				'uri_b' => '',
				'position' => $post->position,
				'created_at' => $post->created_at
			)
		);
		
		$post = new \stdClass;
		$post->title = 'Következő futam bannerje';
		$post->excerpt = '';
		$post->body = "[msr_next_race_banner('F1','2015')]";
		$post->created_at = '';
		$post->post_type = 'widget';
		$post->position = 'sidebar-full';
		$post->uri = '/sidebar-right/next-race-banner';
		
		$post->id = $this->addPost($post)->id;
		
		DB::table('post_to_post')->insertGetId(
			array(
				'uri_a' => $post->uri,
				'uri_b' => '',
				'position' => $post->position,
				'created_at' => $post->created_at
			)
		);
	}

	private function copyUploads() {
		$from= '/var/www/f1info.hu/wp-content/uploads/';
		$to= '/var/www/f1info.hu.new/storage/app/attachments/wp-content/uploads/'; //wp-content/uploads/

		$counter = 0;
		foreach (File::allFiles($from) as $c => $file) {
			# code...
			$from_filename = (string)$file;

			$result = File::makeDirectory(str_replace($from, $to, $from_filename), 0775, true);

			/*
			$file = Input::file('fileUpload');
	        $t = time();

	        \Event::fire('BeforeAddFileVersion', $data);

	        $extension = \File::extension($file->getClientOriginalName());
	        $directory = storage_path() . '/attachments/'.$extension;
	        $filename = $t . "." . md5($file->getClientOriginalName()) . ".{$extension}";

	        $data->success = Input::file('fileUpload')->move($directory, $filename);
	        if($data->success) {
	            $data->storage_path = $directory . '/' . $filename;
	        }
	        */

			if($result) {
				$to_filename = time() . "." . File::extension($from_filename);

				if(File::copy($from_filename, str_replace($from, $to, $from_filename) . '/' . $to_filename)) {
					$attachment = new \stdClass;
					$attachment->title = File::name($from_filename) . '';
					$attachment->excerpt = '';
					$attachment->post_type = 'attachment';
					$attachment->position = 'image';
					//0000-00-00 00:00:00
					$attachment->created_at = date('Y-m-d H:i:s', File::lastModified($filename));
					$attachment->uri = str_replace('/var/www/f1info.hu/', '/', $from_filename) . '';
					$attachment->body = '/var/www/f1info.hu.new/storage/app/attachments' . $attachment->uri . '/' . $to_filename;

					$this->addPost($attachment);
					$counter++;
				}
			}
		}

		echo $counter . "files ";
	}
	
	private function importCategories() {
		$args = array(
			'type'                     => 'post',
			'child_of'                 => 0,
			'parent'                   => '',
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => 1,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'taxonomy'                 => 'category',
			'pad_counts'               => false
		);
		
		$wp_categories = get_categories($args);
		foreach($wp_categories as $i=>$wp_category) {
			$category = new \stdClass;
			$category->title = $wp_category->name . '';
			$category->excerpt = $wp_category->description . '';
			$category->body = $wp_category->description . '';
			$category->post_type = 'post-category';
			$category->uri = str_replace($this->wpURL, '/', get_category_link($wp_category->term_id)) . '';
			
			$category->id = $this->addPost($category)->id;
			
			$this->categories[$wp_category->cat_ID] = $category;
		}
	}
	
	private function importPosts() {
		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'post_date',
			'order'            => 'ASC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => array('post', 'page'),
			'post_mime_type'   => '',
			'post_parent'      => '',
			'suppress_filters' => true,
			'post_status'      => 'publish'
		);

		$wp_posts_array = get_posts( $args );
		$args['post_type'] = 'attachment';
		$args['post_status'] = 'inherit';
		
		$wp_posts_array = array_merge( $wp_posts_array, get_posts( $args ));

		foreach ($wp_posts_array as $wp_post) {
			/*
			Class_Reference/WP_Post

			Member Variable	Variable Type	Notes
			ID	int	The ID of the post
			post_author	string	The post author's user ID (numeric string)
			post_name	string	The post's slug
			post_type	string	See Post Types
			post_title	string	The title of the post
			post_date	string	Format: 0000-00-00 00:00:00
			post_date_gmt	string	Format: 0000-00-00 00:00:00
			post_content	string	The full content of the post
			post_excerpt	string	User-defined post excerpt
			post_status	string	See get_post_status for values
			comment_status	string	Returns: { open, closed }
			ping_status	string	Returns: { open, closed }
			post_password	string	Returns empty string if no password
			post_parent	int	Parent Post ID (default 0)
			post_modified	string	Format: 0000-00-00 00:00:00
			post_modified_gmt	string	Format: 0000-00-00 00:00:00
			comment_count	string	Number of comments on post (numeric string)
			menu_order	string	Order value as set through page-attribute when enabled (numeric string. Defaults to 0)
			*/

			$post = new \stdClass;
			$post->title = $wp_post->post_title . '';
			$post->excerpt = $wp_post->post_excerpt . '';
			$post->body = $wp_post->post_content . '';
			$post->post_type = $wp_post->post_type . '';
			$post->created_at = $wp_post->post_date_gmt . '';
			$post->uri = str_replace($this->wpURL, '/', get_permalink($wp_post->ID)) . '';

			$post->id = $this->addPost(
					$post,
					str_replace(
						$this->wpURL,
						'/',
						get_category_link(
							get_the_category($wp_post->ID)[0]
							->term_id
						)
					)
				)
				->id;
			
			$parent_uri = '';
			if($wp_post->post_type = 'post') {
				$cat = get_the_category($post->ID);
				if($cat && count($cat) > 0) {
					foreach($cat as $wp_category) {
						$id = $wp_category->term_id;
						$post->position = 'category-listing';
						$this->addPost($post, $this->categories[$id]->uri);
					}
				}
			}
			
			$this->importPostAttachments($wp_post, $post);
		}
		
		return true;
	}
	
	private function importPostAttachments($wp_post, $post) {
		$wp_attachments = get_attached_media('image', $wp_post->ID);
		foreach ($wp_attachments as $wp_attachment) {
			$attachment = new \stdClass;
			$attachment->title = $wp_attachment->post_title . '';
			$attachment->excerpt = $wp_attachment->post_excerpt . '';
			$attachment->post_type = 'attachment';
			$attachment->position = 'image';
			$attachment->created_at = $wp_attachment->post_date_gmt . '';
			$attachment->uri = str_replace($this->wpURL, '/', wp_get_attachment_url($wp_attachment->ID)) . '';
			$attachment->body = '/var/www/f1info.hu.new/storage/app/attachments' . $attachment->uri;

			$this->addPost($attachment, $post->uri);
		}
		
		//addition for thumbnails
		$thumb_id = get_post_thumbnail_id($wp_post->ID);
		$thumb_args = array(
			'post_type' => 'attachment',
			'post_status' => null,
			'post_parent' => $wp_post->ID,
			'include'  => $thumb_id
		);
		$wp_attachment = get_posts($thumb_args);
		$wp_thumb = $wp_attachment[0];
		$attachment = new \stdClass;
		$attachment->title = $wp_thumb->post_title . '';
		$attachment->excerpt = $wp_thumb->post_excerpt . '';
		$attachment->created_at = $wp_thumb->post_date_gmt . '';
		$attachment->post_type = 'attachment';
		$attachment->position = 'post-thumbnail';
		$attachment->uri = str_replace($this->wpURL, '/', wp_get_attachment_url($wp_thumb->ID)) . '';
		$attachment->body = '/var/www/f1info.hu.new/storage/app/attachments' . $attachment->uri;
		
		if($attachment->uri !== '') {
			//echo $attachment->uri . "\n";
			$this->addPost($attachment, $post->uri);
			
			$articleimg = get_post_meta( $wp_post->ID, 'articleimg', true );
			
			if(!empty($articleimg)) {
				$rel = DB::table('post_to_post')->insertGetId(
					array(
						'uri_a' => str_replace($this->wpURL, '/', $articleimg) . '',
						'uri_b' => $post->uri,
						'position' => 'featuredImage',
						'created_at' => $post->created_at
					)
				);
			}
			elseif(count($wp_attachments) > 0) {
				$rel = DB::table('post_to_post')->insertGetId(
					array(
						'uri_a' => str_replace($this->wpURL, '/', wp_get_attachment_url($wp_attachment->ID)) . '',
						'uri_b' => $post->uri,
						'position' => 'featuredImage',
						'created_at' => $post->created_at
					)
				);
			}
		}
	}

	private function addPost($post, $parent_uri = '') {
		$rules = array(
		    'uri' => 'unique:posts' //only for import purpose...
		);

		$validator = Validator::make((array)$post, $rules);

		if ($validator->passes()) {
		    $post->id = DB::table('posts')->insertGetId(
			    array(
			    	'internal_name' => $post->title,
			    	'title' => $post->title,
					'excerpt' => $post->excerpt,
					'body' => $post->body,
					'post_type' => $post->post_type,
					'uri' => $post->uri,
					'created_at' => $post->created_at
			    )
			);
		}

		//$post->id = DB::table('posts')->where('uri', $post->uri)->pluck('id');

		if($parent_uri !== '') {
			$rel = DB::table('post_to_post')->insertGetId(
			    array(
			    	'uri_a' => $post->uri,
			    	'uri_b' => $parent_uri,
					'position' => isset($post->position) ? $post->position : 'none',
					'created_at' => $post->created_at
			    )
			);
		}

		return $post;
	}
	
	private function importMenus() {
		$wp_menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		
		foreach($wp_menus as $wp_menu) {
			//echo $wp_menu->name . "\n";
			$link = new \stdClass;
		}
	}
	
	private function addLink($link, $parent_id = 0) {
		$rel = DB::table('links')->insertGetId(
			array(
				'target' => $link->target,
				'url' => $link->url,
				'text' => $link->text,
				'order' => 1,
				'parent_id' => $parent_id,
				'access_level' => 0
			)
		);
		$link->id = $rel;
		
		return $link;
	}
	
	private function importUsers() {
		$args = array(
			//'blog_id'      => $GLOBALS['blog_id'],
			'role'         => '',
			'meta_key'     => '',
			'meta_value'   => '',
			'meta_compare' => '',
			'meta_query'   => array(),
			'include'      => array(),
			'exclude'      => array(),
			'orderby'      => 'login',
			'order'        => 'ASC',
			'offset'       => '',
			'search'       => '',
			'number'       => '',
			'count_total'  => false,
			'fields'       => 'all',
			'who'          => ''
		);
		
		$wp_users = get_users($args);
		foreach($wp_users as $wp_user) {
			/*
			WP: user_login, user_pass, user_nicename, user_email, user_url, user_registered, user_activation_key, user_status, display_name
			
			$table->increments('id');
			$table->string('name');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->integer('access_level');
			$table->rememberToken();
			$table->timestamps();
			*/
			
			$user = new \stdClass;
			$user->name = $wp_user->display_name;
			$user->email = $wp_user->user_email;
			$user->password = $wp_user->user_pass;
			
			$this->addUser($user);
		}
	}
	
	private function addUser($user) {
		$rel = DB::table('users')->insertGetId(
			array(
				'name' => $user->name,
				'email' => $user->email,
				'password' => Hash::make($user->password),
				'access_level' => ($user->email == 'koeroesi86@gmail.com' ? 2 : 1),
				'confirmed' => 1
			)
		);
		$user->id = $rel;
		
		return $user;
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('posts')->truncate();
		DB::table('post_to_post')->truncate();
		DB::table('links')->truncate();
		DB::table('users')->truncate();

		$success = File::deleteDirectory('/var/www/f1info.hu.new/storage/app/attachments/');
	}

}
