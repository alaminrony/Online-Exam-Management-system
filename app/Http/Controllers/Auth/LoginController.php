<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth; //model class
use Helper; //model class
use App\User;
use Session;
use App\LogHistory;
use App\PasswordSetup;
use Jenssegers\Agent\Agent;

class LoginController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

use AuthenticatesUsers;

    //only override this function for adding condition status wise user can login 
    //directory main file where implements this credentials function LARAVEL 5.6 default : atms\vendor\laravel\framework\src\Illuminate\Foundation\Auth\AuthenticatesUsers.php
    protected function credentials(Request $request) {
        $data = $request->only($this->username(), 'password');
        $data['status'] = 'active';
        return $data;
    }

    //user mail change for username
    //directory main file where implements this credentials function LARAVEL 5.6 default : atms\vendor\laravel\framework\src\Illuminate\Foundation\Auth\AuthenticatesUsers.php
    public function username() {
        return 'username';
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';
//    protected $redirectTo = '/dashboard';
    public function redirectTo() {
        $user = Auth::user();
        $passwordExpeiryDate = PasswordSetup::first();
        $passwordChangedDate = date_create($user->password_changed_at);
        $modifyDate = date_modify($passwordChangedDate, "+{$passwordExpeiryDate->expeired_of_password} days");
        $sumOfChangedAndExpiredDate = date_format($modifyDate, "Y-m-d");
        $presentLoginDate = date("Y-m-d");
        if (Auth::user()->first_login == '0') {
            return '/forceChangePassword?type=2';
        } elseif ($sumOfChangedAndExpiredDate < $presentLoginDate) {
            return '/forceChangePassword?type=1';
        }
        return '/dashboard';
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }

    //directory main file where implements this credentials function LARAVEL 5.6 default : atms\vendor\laravel\framework\src\Illuminate\Foundation\Auth\AuthenticatesUsers.php
    public function logout(Request $request) {
        $userLogInfo = LogHistory::where(['user_id' => Auth::user()->id, 'login_date' => date('Y-m-d')])->where('type_id', '1')->first();
        $preLogInfo = !empty($userLogInfo->login_info) ? $userLogInfo->login_info : '';
        $preLogInfoArr = json_decode($preLogInfo, true);


        $logoutArr = [];
        if (!empty($preLogInfoArr)) {
            foreach ($preLogInfoArr as $key => $preLogInfo) {
                if ($key == Session::get('login_id')) {
                    $logoutArr[$key]['operating_system'] = $preLogInfo['operating_system'];
                    $logoutArr[$key]['browser'] = $preLogInfo['browser'];
                    $logoutArr[$key]['browser_ip'] = $preLogInfo['browser_ip'];
                    $logoutArr[$key]['login_datetime'] = $preLogInfo['login_datetime'];
                    $logoutArr[$key]['logout_datetime'] = date('Y-m-d H:i:s');
                } else {
                    $logoutArr[$key]['operating_system'] = $preLogInfo['operating_system'];
                    $logoutArr[$key]['browser'] = $preLogInfo['browser'];
                    $logoutArr[$key]['browser_ip'] = $preLogInfo['browser_ip'];
                    $logoutArr[$key]['login_datetime'] = $preLogInfo['login_datetime'];
                    $logoutArr[$key]['logout_datetime'] = $preLogInfo['logout_datetime'];
                }
            }
        }
        if (!empty($logoutArr)) {
            $finalData = json_encode($logoutArr);
            $userLogInfo->login_date = date('Y-m-d');
            $userLogInfo->login_info = $finalData;
            $userLogInfo->save();
        }


        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect('/');
    }

    public function authenticated(Request $request) {
        $request->session()->put('paginatorCount', __('label.PAGINATION_COUNT'));

        $agent = new Agent();
        $platform = $agent->platform();
        $browser = $agent->browser();
        $browser_ip = $request->ip();

        $logInfo = [];
        $uniquid = uniqid();
        $request->session()->put('login_id', $uniquid);
        $logInfo[$uniquid]['operating_system'] = $platform;
        $logInfo[$uniquid]['browser'] = $browser;
        $logInfo[$uniquid]['browser_ip'] = $browser_ip;
        $logInfo[$uniquid]['login_datetime'] = date('Y-m-d H:i:s');
        $logInfo[$uniquid]['logout_datetime'] = '';
        $logInfoJson = json_encode($logInfo);


        $userLogInfo = LogHistory::where(['user_id' => Auth::user()->id, 'login_date' => date('Y-m-d')])
                        ->where('type_id', '1')->first();

        if (!empty($userLogInfo->login_info)) {
            $preLogInfo = json_decode($userLogInfo->login_info, true);
        }

        if (!empty($preLogInfo)) {
            $finalLogInfoJson = array_merge($preLogInfo, $logInfo);
            $logInfoJson = json_encode($finalLogInfoJson);
            $userLogInfo->login_info = $logInfoJson;
            $userLogInfo->save();
        } else {
            $logHistory = new LogHistory;
            $logHistory->user_id = Auth::user()->id;
            $logHistory->type_id = 1;
            $logHistory->login_date = date('Y-m-d');
            $logHistory->login_info = $logInfoJson;
            $logHistory->save();
        }
    }

}
