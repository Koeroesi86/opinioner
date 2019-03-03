<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 250)->unique()->index();
			$table->string('value', 250);
		});

		// Insert some stuff
	    DB::table('settings')->insert([
	        [
	            'name' => 'system_sitename',
	            'value' => 'F1infó'
	        ],
	        [
	            'name' => 'system_title_separator',
	            'value' => ' | '
	        ],
	        [
	            'name' => 'system_template',
	            'value' => '2014newbrand'
	        ]
	    ]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

}
