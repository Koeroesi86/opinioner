<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;

class WidgetController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public static function index()
	{
		//
		$widgets = DB::table('widgets');
		
		foreach ($widgets as $widget) {
			if(is_object($widget) && !empty($widget))
				$widget->body = WidgetController::parse($widget);
		}

		return $widgets;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public static function show($id)
	{
		$widget = DB::table('widgets')
					->where('id', $id)
					->get();
		if(!is_object($widget) || empty($widget)) {
			return '';
		}

		$widget->body = WidgetController::parse($widget);

		return $widget->body;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function listing($position)
	{
		$widgets = DB::table('widgets')
					->where('position', $position)
					->get();
		
		foreach ($widgets as $widget) {
			$widget->body = WidgetController::parse($widget);
		}

		return $widgets;
	}

	public static function parse($widget) {
		if(!is_object($widget)) return $widget;
		if(!empty($widget->classname)) {
			$cl = 'Plugins\\' . $widget->classname;
			if(class_exists($cl)) {
				$cl = new $cl;
				if(method_exists($cl, 'getWidget')) {
					return $cl->getWidget($widget->body);
				}
			}
		}

		return $widget->body;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
