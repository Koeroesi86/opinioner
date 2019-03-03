<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;

/**
 * Class ApiBaseController
 * Loads some defaults to use on API
 * @package App\Http\Controllers\Api
 */
class ApiBaseController extends Controller
{
    /** @var User|null */
    protected $user;
    /** @var integer */
    protected $access_level;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = Auth::user();
        $this->access_level = 0;
        if ($this->user instanceof User) {
            $this->access_level = $this->user->access_level;
        }
    }

    protected function checkAccessLevel($level = 0) {
        return $this->access_level >= $level;
    }
}
