<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use DB;

abstract class Controller extends BaseController {
	public $settings = array();
	public $template = 'default';
	
	public function getSettings() {
		$set = DB::table('settings')->get();

		foreach ($set as $row) {
			$this->settings[$row->name] = $row->value;
		}

		if(isset($this->settings['system_template'])) {
			$this->template = $this->settings['system_template'];
		}
	}

	use DispatchesJobs, ValidatesRequests;

}
