<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Response;

class AdminController extends Controller
{
    private $access_level = 0;
    /** @var User|null  */
    private $user;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        //$this->middleware('guest');
        $this->getSettings();

        $this->user = Auth::user();

        if($this->user && isset($this->user->access_level)) {
            $this->access_level = $this->user->access_level;
        }

        if($this->template) {
            \LaravelGettext::setDomain($this->template);
        }
    }

    /**
     * Show the application dashboard to the user.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        if($this->access_level <= 1) {
            return redirect('/');
        }
        return view('page.templates.default.admin');
    }
}
