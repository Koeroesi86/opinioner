<?php
namespace App\Http\Controllers\Auth;

use App\Exceptions\InvalidConfirmationCodeException;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Input;
use Mail;
use Redirect;
use Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    /** @var Guard */
    private $auth;

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->getSettings();
        $this->auth = $auth;

        $this->middleware('guest', ['except' => array('doLogout', 'showChangePassword', 'doForgottenPassword', 'showForgottenPassword')]);
    }

    /**
     * Shows registration form
     *
     * @return Factory|RedirectResponse|View
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return Redirect::to('/');
        }

        $temp = "page.templates.default.particles.login";
        if (view()->exists("page.templates.{$this->template}.particles.login")) {
            $temp = "page.templates.{$this->template}.particles.login";
        }

        return view($temp)->with(array(
                'template' => $this->template,
                'uri' => 'login',
                'title' => _('Login'),
                'links' => array(),
                'settings' => $this->settings
            )
        );
    }

    /**
     * Attempts login
     *
     * @return RedirectResponse
     */
    public function doLogin()
    {
        if (Auth::check()) {
            return Redirect::to('/');
        }

        // validate the info, create rules for the inputs
        $rules = array(
            'email' => 'required|email', // make sure the email is an actual email
            'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('auth/login')
                ->withErrors($validator)// send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {
            // create our user data for the authentication
            $userdata = array(
                'email' => Input::get('email'),
                'password' => Input::get('password'),
                'confirmed' => 1
            );
            $rememberme = Input::get('rememberme') == 'forever';

            // attempt to do the login
            if (Auth::attempt($userdata, $rememberme)) {
                // validation successful!
                return Redirect::to('/');
            } else {
                // validation not successful, send back to form
                return Redirect::to('auth/login');

            }

        }
    }

    /**
     * Attempts logout
     *
     * @return RedirectResponse
     */
    public function doLogout()
    {
        Auth::logout(); // log the user out of our application

        return Redirect::to('auth/login'); // redirect the user to the login screen
    }

    /**
     * Shows registration form
     * @return RedirectResponse|View|Factory
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return Redirect::to('/');
        }

        $temp = "page.templates.default.particles.register";
        if (view()->exists("page.templates.{$this->template}.particles.register")) {
            $temp = "page.templates.{$this->template}.particles.register";
        }

        return view($temp)->with(array(
                'template' => $this->template,
                'uri' => 'login',
                'title' => _('Register'),
                'links' => array(),
                'settings' => $this->settings
            )
        );
    }

    /**
     * Attempts register
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function doRegister(Request $request)
    {
        $input = Input::only([
            'name',
            'email',
            'password',
            'password_confirmation'
        ]);

        $validator = $this->validator($input);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $confirmationCode = str_random(30);

        $this->create([
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'password' => Input::get('password'),
            'confirmation_code' => $confirmationCode,
        ]);

        try {
            Mail::send('email.templates.default.verify',
                [
                    'confirmationCode' => $confirmationCode,
                    'emailAddress' => Input::get('email')
                ],
                function (Message $message) {
                    $message->to(Input::get('email'), Input::get('name'))
                        ->subject(_('Verify your email address'));
                }
            );
        } catch (\Exception $exception) {

        }

        $request->session()->flash('message', _('Thanks for signing up! Please check your email.'));

        return Redirect::to('/');
    }

    /**
     * Checks confirmation code
     *
     * @param $emailAddress
     * @param $confirmationCode
     * @return RedirectResponse
     * @throws InvalidConfirmationCodeException
     */
    public function doConfirm($emailAddress, $confirmationCode)
    {
        if (!$confirmationCode) {
            throw new InvalidConfirmationCodeException;
        }

        /** @var User $user */
        $user = User::where('confirmation_code', $confirmationCode)
            ->where('email', $emailAddress)
            ->first();

        if (!($user instanceof User)) {
            throw new InvalidConfirmationCodeException;
        }

        $user->confirmed = 1;
        if ($user->access_level == 0) $user->access_level = 1;
        $user->confirmation_code = null;
        $user->save();

        return Redirect::route('auth/login');
    }

    /**
     * Shows password change form
     *
     * @param string $emailAddress
     * @param string $forgottenCode
     * @return Factory|RedirectResponse|View
     */
    public function showChangePassword($emailAddress = "", $forgottenCode = "")
    {
        $forgotten = DB::table('password_resets')
            ->where('email', $emailAddress)
            ->where('token', $forgottenCode)
            ->get();

        if (!Auth::check() && count($forgotten) == 0) {
            return Redirect::to('/');
        }

        $user = count($forgotten) == 0 ? Auth::user() : User::where('email', $forgotten->first()->email)->first();

        $temp = "page.templates.default.particles.change_password";
        if (view()->exists("page.templates.{$this->template}.particles.change_password")) {
            $temp = "page.templates.{$this->template}.particles.change_password";
        }

        return view($temp)->with(array(
                'uri' => 'auth/change-password',
                'title' => _('Change Password'),
                'user' => $user,
                'links' => array(),
                'template' => $this->template,
                'settings' => $this->settings
            )
        );
    }

    /**
     * Shows password change confirmation
     *
     * @return View|Factory
     */
    public function showForgottenPassword()
    {
        $temp = "page.templates.default.particles.forgotten_password";
        if (view()->exists("page.templates.{$this->template}.particles.forgotten_password")) {
            $temp = "page.templates.{$this->template}.particles.forgotten_password";
        }

        return view($temp)->with(array(
                'uri' => 'auth/password-reminder',
                'title' => _('Forgotten Password'),
                'links' => array(),
                'template' => $this->template,
                'settings' => $this->settings
            )
        );
    }

    /**
     * Sends forgotten password email
     *
     * @return RedirectResponse|View|Factory
     */
    public function doForgottenPassword()
    {
        $forgottenCode = str_random(30);

        $user = User::where('email', Input::get('email'))->first();
        var_dump($user);

        if ($user->count() == 0) {
            $temp = "page.templates.default.particles.forgotten_password";
            if (view()->exists("page.templates.{$this->template}.particles.forgotten_password")) {
                $temp = "page.templates.{$this->template}.particles.forgotten_password";
            }
            return view($temp)->with(array(
                    'uri' => 'auth/password-reminder',
                    'title' => _('Forgotten Password'),
                    'error' => _("No user with e-mail: ") . Input::get('email'),
                    'links' => array(),
                    'template' => $this->template,
                    'settings' => $this->settings
                )
            );
        }

        DB::table('password_resets')->insertGetId(
            array(
                'email' => $user->email,
                'token' => $forgottenCode,
                'created_at' => time()
            )
        );

        $temp = "email.templates.default.forgotten_password";
        if (view()->exists("email.templates.{$this->template}.forgotten_password")) {
            $temp = "email.templates.{$this->template}.forgotten_password";
        }

        Mail::send($temp,
            array(
                'forgottenCode' => $forgottenCode,
                'emailAddress' => $user->email
            ),
            function ($message) use ($user) {
                $message
                    ->to($user->email, $user->name)
                    ->subject(_('Forgotten password'));
            }
        );

        return Redirect::to('auth/login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->confirmation_code = $data['confirmation_code'];
        $user->access_level = '0';
        $user->save();

        return $user;
    }
}
