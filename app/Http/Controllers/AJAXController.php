<?php
namespace App\Http\Controllers;

use DB;
use Config;
use File;
use Response;
use Auth;

class AJAXController extends PageController {
    /**
     * Create a new controller instance.
     *
     */
	public function __construct()
	{
	    parent::__construct();
	}

	/**
     * @return \Illuminate\Http\Response
     */
    public function render() {
		$data = new \stdClass;
		
		\Event::fire('BeforeAJAXResponse', $data);
		
		return Response::make(json_encode($data), 200, array('Content-type' => 'application/json'));
    }

}
