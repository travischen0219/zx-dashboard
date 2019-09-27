<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Model\Login_log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


// require_once('../resources/libs/code/Code.class.php');
session_start();

class LoginController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        $login_ip = $request->ip();
        $login_browser = $request->userAgent();

        // $code = new \Code;
        // $_code = $code->get();

        // if (strtoupper($request->sendCode) != $_code) {
        if (false) {
            // $login_log = new Login_log;
            // $login_log->ip = $login_ip;
            // $login_log->browser = $login_browser;
            // $login_log->login_at = Now();
            // $login_log->status = "wrong captcha";
            // $login_log->save();
            // return back()->with('error','驗證碼錯誤');
        } else {
            if($request->setType == 'code'){
                $user = User::where('staff_code',strtoupper($request->usercode))->first();

                if($user){
                    if($user->status != 100){
                        $login_log = new Login_log;
                        $login_log->ip = $login_ip;
                        $login_log->browser = $login_browser;
                        $login_log->login_at = Now();
                        $login_log->status = "no authorization";
                        $login_log->user = $user->id;
                        $login_log->save();
                        return redirect()->back()->with('error','無登入權限');
                    } else if($user->status == 1){
                        if(Hash::check($request->password , $user->password)){
                            $user->last_IP = $request->ip();
                            $user->last_login_date_time = Now();
                            $user->save();
                            session(['admin_user' => $user]);

                            $login_log = new Login_log;
                            $login_log->ip = $login_ip;
                            $login_log->browser = $login_browser;
                            $login_log->login_at = Now();
                            $login_log->status = "success";
                            $login_log->user = $user->id;
                            $login_log->save();

                            return redirect()->route('dashboard');
                        } else {
                            $login_log = new Login_log;
                            $login_log->ip = $login_ip;
                            $login_log->browser = $login_browser;
                            $login_log->login_at = Now();
                            $login_log->status = "wrong password";
                            $login_log->user = $user->id;
                            $login_log->save();
                            return redirect()->back()->with('error','員工編號或密碼錯誤');
                        }

                    }

                } else {
                    $login_log = new Login_log;
                    $login_log->ip = $login_ip;
                    $login_log->browser = $login_browser;
                    $login_log->login_at = Now();
                    $login_log->status = "wrong staff code";
                    $login_log->save();
                    return redirect()->back()->with('error','員工編號或密碼錯誤');
                }
            } else if($request->setType == 'name'){
                $user = User::where('username',strtoupper($request->username))->first();
                if($user){
                    if($user->status != 1){
                        $login_log = new Login_log;
                        $login_log->ip = $login_ip;
                        $login_log->browser = $login_browser;
                        $login_log->login_at = Now();
                        $login_log->status = "no authorization";
                        $login_log->user = $user->id;
                        $login_log->save();
                        return redirect()->back()->with('error','無登入權限');
                    } else if($user->status == 1){
                        if(Hash::check($request->password , $user->password)){
                            $user->last_IP = $request->ip();
                            $user->last_login_date_time = Now();
                            $user->save();
                            session(['admin_user' => $user]);
                            $login_log = new Login_log;
                            $login_log->ip = $login_ip;
                            $login_log->browser = $login_browser;
                            $login_log->login_at = Now();
                            $login_log->status = "success";
                            $login_log->user = $user->id;
                            $login_log->save();
                            return redirect()->route('dashboard');

                        } else {
                            $login_log = new Login_log;
                            $login_log->ip = $login_ip;
                            $login_log->browser = $login_browser;
                            $login_log->login_at = Now();
                            $login_log->status = "wrong password";
                            $login_log->user = $user->id;
                            $login_log->save();
                            return redirect()->back()->with('error','帳號或密碼錯誤');
                        }
                    }
                } else {
                    $login_log = new Login_log;
                    $login_log->ip = $login_ip;
                    $login_log->browser = $login_browser;
                    $login_log->login_at = Now();
                    $login_log->status = "wrong staff account";
                    $login_log->save();
                    return redirect()->back()->with('error','帳號或密碼錯誤');
                }

            }

        }

    }

    public function code()
    {
        $code = new \Code;
        $code->make();
    }

    public function logout()
    {
        session()->forget('admin_user');
        return redirect()->route('home');
    }
}
