<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Traits\ResponseTraits;
use App\Traits\ValidateTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use App\Exceptions\RoleAdminException;

class AuthController extends Controller
{
    use ValidateTraits, ResponseTraits;

    public $manager;
    public $admin;
    public $user;
    private $modelUser;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->manager = Config::get('auth.roles.manager');
        $this->admin = Config::get('auth.roles.admin');
        $this->user = Config::get('auth.roles.user');
        $this->modelUser = new User();
    }

    // Admin

    /**
     *  Request screen login
     *
     * @return Application|Factory|View
     */
    public function initScreenLoginAdmin()
    {
        return view('admin.login');
    }

    /**
     * Login admin
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function loginAdmin(Request $request)
    {
        try {
            $this->validateLogin($request);
            $credentials = request(['username', 'password']);
            $user = User::where([
                ['username', $request->username],
                ['is_deleted', false]])
                ->first();
            if (!Auth::attempt($credentials) || !$user) {
                $message = Lang::get('message.wrong_email_password');
                return redirect(route('screen_admin_login'))->with('message', $message);
            }
            $user->createToken('authToken')->plainTextToken;
            return redirect(route('screen_admin_home'));
        } catch (Exception $e) {
            return back()->with('message', $e->getMessage());
        }
    }

    /**
     * Request screen forgot password admin
     *
     * @return Application|Factory|View
     */
    public function initScreenForgotPasswordAdmin()
    {
        return view('admin.forgot_password');
    }

    /**
     * Credential email
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function forgotPasswordAmin(Request $request)
    {
        try {
            $this->validateForgotPassword($request);
            $user = User::where('email', $request->email)->first();
            if ($user->role->name !== 'admin') {
                $message = Lang::get('message.not_have_role');
                return back()->with('message', $message);
            }
            $token = Str::random('35');
            $user->remember_token = $token;
            $user->save();

            $link_reset_pass = url('admin/reset-password?email=' . $user->email . '&remember_token=' . $token);
            $data = array("name" => $user->name, "body" => $link_reset_pass, "email" => $user->email);

            Mail::send('mail.mail_forgot_password', $data, function ($message) use ($data) {
                $message->to($data['email'])->subject('Reset password');
            });
            $message = Lang::get('message.check_mail');
            return back()->with('message', $message);
        } catch (Exception $e) {
            return back()->with('message', $e->getMessage());
        }
    }

    /**
     * Request screen update password admin
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function initScreenUpdatePasswordAdmin(Request $request)
    {
        return view('admin.reset_password')->with('request', $request);
    }

    /**
     * Update password admin
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function updatePasswordAdmin(Request $request)
    {
        try {
            $this->validateResetPassword($request);
            $user = User::where([
                ['email', $request->email],
                ['remember_token', $request->token]
            ])->first();

            if ($user) {
                if ($user->role->name !== 'admin') {
                    $message = Lang::get('message.check_mail');
                    return back()->with('message', $message);
                }
                $user->password = Hash::make($request->password);
                $user->remember_token = Str::random('35');
                $user->save();
                $message = Lang::get('message.reset_pass_done');
                return redirect(route('screen_admin_login'))->with('message', $message);
            }
            $message = Lang::get('message.link_expired');
            return back()->with('message', $message);
        } catch (Exception $e) {
            return back()->with('message', $e->getMessage());
        }
    }

    /**
     * Request screen home
     *
     * @return Application|Factory|View
     * @throws RoleAdminException
     */
    public function indexAdmin()
    {
        $this->checkRoleAdmin();
        return view('admin.home');
    }

    /**
     * Logout
     *
     * @return Application|RedirectResponse|Redirector
     * @throws RoleAdminException
     */
    public function logoutAdmin()
    {
        $this->checkRoleAdmin();
        Auth::guard('web')->logout();
        return redirect(route('screen_admin_login'));
    }


    // User

    /**
     * Request screen register
     *
     * @return Application|Factory|View
     */
    public function initScreenRegister()
    {
        return view('user.register');
    }

    /**
     * Credential register
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function register(Request $request)
    {
        try {
            $this->validateRegister($request);

            $roles = Role::where('name', 'user')->first();
            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => $roles->id
            ]);
            $credentials = request(['username', 'password']);
            if (!Auth::attempt($credentials)) {
                $message = Lang::get('message.wrong_email_password');
                return redirect(route('screen_admin_login'))->with("message", $message);
            }
            $user->createToken('authToken')->plainTextToken;

            return redirect(route('screen_admin_home'));
        } catch (Exception $e) {
            return back()->with('message', $e->getMessage());
        }
    }

    /**
     *  Request screen login
     *
     * @return Application|Factory|View
     */
    public function initScreenLogin()
    {
        return view('user.login');
    }

    /**
     * Credential login
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function login(Request $request)
    {
        try {
            $this->validateLogin($request);
            $credentials = request(['username', 'password']);
            $user = User::where('username', $request->username)->first();
            if (!Auth::attempt($credentials) || $user->role->name !== $this->user) {
                $message = Lang::get('message.wrong_email_password');
                return redirect(route('screen_login'))->with("message", $message);
            }
            $user->createToken('authToken')->plainTextToken;
            return redirect(route('screen_home'));
        } catch (Exception $e) {
            return back()->with('message', $e->getMessage());
        }
    }

    /**
     * Request screen home
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $products = Product::where([['active', '1'], ['is_deleted', '0']])->orderBy('id', 'desc')->get();
        return view('user.home')->with('products', $products);
    }

    /**
     * Request screen forgot password
     *
     * @return Application|Factory|View
     */
    public function initScreenForgotPassword()
    {
        return view('user.forgot_password');
    }

    /**
     * Credential email
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function forgotPassword(Request $request)
    {
        try {
            $this->validateForgotPassword($request);
            $user = User::where('email', $request->email)->first();
            $token = Str::random('35');
            $user->remember_token = $token;
            $user->save();

            $link_reset_pass = url('reset-password?email=' . $user->email . '&remember_token=' . $token);
            $data = array("name" => $user->name, "body" => $link_reset_pass, "email" => $user->email);

            Mail::send('mail.mail_forgot_password', $data, function ($message) use ($data) {
                $message = Lang::get('message.reset_pass');
                $message->to($data['email'])->subject($message);
            });
            $message = Lang::get('message.check_mail');
            return back()->with('message', $message);
        } catch (Exception $e) {
            return back()->with('message', $e->getMessage());
        }
    }

    /**
     * Request screen update password
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function initScreenUpdatePassword(Request $request)
    {
        return view('user.reset_password')->with('request', $request);
    }

    /**
     * Update password
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function updatePassword(Request $request)
    {
        try {
            $this->validateResetPassword($request);
            $user = User::where([
                ['email', $request->email],
                ['remember_token', $request->token]])
                ->first();

            if ($user) {
                $user->password = Hash::make($request->password);
                $user->remember_token = Str::random('35');
                $user->save();
                $message = Lang::get('message.reset_pass_done');
                return redirect(route('screen_login'))->with('message', $message);
            }
            $message = Lang::get('message.link_expired');
            return back()->with('message', $message);
        } catch (Exception $e) {
            return back()->with('message', $e->getMessage());
        }
    }

    /**
     * Logout
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect(route('screen_home'));
    }

    /**
     * Init screen info user
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function initScreenInfo()
    {
        try {
            if (Auth::user()->role->name === $this->user) {
                $user = User::find(Auth::id());
                return view('user.detail')->with('user', $user);
            } else {
                return redirect(route('screen_home'));
            }
        } catch (Exception $e) {
            return redirect(route('screen_home'));
        }
    }

    /**
     * Update info user
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function updateInfo(Request $request)
    {
        try {
            if ($this->checkRoleUser()) {
                $response = $this->modelUser->updateInfo($request);
                $message = $response['message'];
            } else {
                $message = Lang::get('message.not_have_role');
                return redirect(route('screen_admin_login'))->with('message', $message);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }

    /**
     * Change info user
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function changePassword(Request $request)
    {
        try {
            if ($this->checkRoleUser()) {
                $response = $this->modelUser->changePassword($request);
                $message = $response['message'];
            } else {
                $message = Lang::get('message.not_have_role');
                return redirect(route('screen_admin_login'))->with('message', $message);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        return back()->with('message', $message);
    }
}
