<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;


class AuthController extends Controller
{
    public function login(){

        if (auth()->user()) {
            return redirect()->route('admin.dashboard');
        }
        return view('backend.auth.login');
    }

    public function postLogin(Request $request){

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            
            if(isset($request->remember))
            {
                setcookie("user", $request->username, time()+(10 * 365 * 24 * 60 * 60));
                setcookie("pass", $request->password, time()+(10 * 365 * 24 * 60 * 60));
            }
            else 
            {
                setcookie("user", "");
                setcookie("pass", "");
            }
            
            return redirect()->intended('admin/dashboard')
                        ->withSuccess('You have Successfully loggedin');
        }
  
        return redirect("admin")->withSuccess('Oppes! You have entered invalid credentials');
        
    }

}
