<?php
/**
 * Created by PhpStorm.
 * User: Lourence
 * Date: 11/11/2016
 * Time: 12:42 PM
 */

namespace App\Http\Controllers;

use App\User;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function change_password() {
        return view('auth.passwords.reset');
    }
    public function save_changes(Request $request){
        $validator = Validator::make(
            array(
                'current_password' => $request->input('current_password'),
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation')
            ),
            array(
                'current_password' => 'required',
                'password' => 'required|max:15',
                'password_confirmation' => 'same:password'
            )
        );
        if($validator->fails()){
           return redirect('/change/password')->with('error', $validator->messages());
        }
        $user = User::find(Session::get('auth')->id);
        if(Hash::check($request->input('current_password'),$user->password)){
            $user->password = Hash::make($request->input('password_confirmation'));
            $user->save();
            Session::flush();
            return redirect('/')->with('ok', 'Password succesfully changed. Login now to your account.');
        }
        return redirect('/change/password')->with('not_match','Current password invalid');
    }
    public function change()
    {
            return;
            $user = User::find(418);
            
            $user->password = Hash::make('0137');
            $user->save();
            Session::flush();
            return "Reseted";
        
    }
}