<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Collection;

class LinkController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @param int $menu_id
     * @return Response|Collection
     */
	public static function index($menu_id = 0)
	{
		//
		$menu = DB::table('links')
					->where('parent_id', $menu_id)
					->orderBy('order', 'asc')
					->get();

		if($menu instanceof Collection) {
//		    $menu->items = LinkController::index($menu->id);
        }

		return $menu;
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
		return DB::table('links')
					->where('id', $id)
					->get();
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
