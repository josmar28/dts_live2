<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Login;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginCtrl extends Controller
{

    public function index()
    {
        if($login = Session::get('auth')){
            return redirect('home');
        }else{
            Session::flush();
            return view('auth.login');
        }
    }


    public function validateLogin(Request $req)
    {
        $login = User::where('username',$req->username)
            ->first();
        if($login)
        {
            if(Hash::check($req->password,$login->password))
            {
                Session::put('auth',$login);
                $last_login = date('Y-m-d H:i:s');
                User::where('id',$login->id)
                    ->update([
                        'last_login' => $last_login,
                        'login_status' => 'login'
                    ]);
                $checkLastLogin = self::checkLastLogin($login->id);

                $l = new Login();
                $l->userId = $login->id;
                $l->login = $last_login;
                $l->status = 'login';
                $l->save();

                if($checkLastLogin > 0 ){
                    Login::where('id',$checkLastLogin)
                        ->update([
                            'logout' => $last_login
                        ]);
                }

             return redirect('home');
            }
            else{
                return Redirect::back()->with('error','These credentials do not match our records')->with('username',$req->username);
            }
        }
        else{
            return Redirect::back()->with('error','These credentials do not match our records')->with('username',$req->username);
        }
    }

    function checkLastLogin($id)
    {
        $start = Carbon::now()->startOfDay();
        $end = Carbon::now()->endOfDay();
        $login = Login::where('userId',$id)
                    ->whereBetween('login',[$start,$end])
                    ->orderBy('id','desc')
                    ->first();
        if($login && (!$login->logout>=$start && $login->logout<=$end)){
            return true;
        }

        if(!$login){
            return false;
        }

        return $login->id;
    }

}
