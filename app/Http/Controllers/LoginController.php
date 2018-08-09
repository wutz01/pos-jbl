<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
  public function __construct () {

  }

  public function login () {
    if (Auth::check()) {
      return redirect()->route('dashboard');
    }
    return view('login');
  }

  public function authenticate (Request $request) {

  	$username = $request->input('username');
  	$password = $request->input('password');

  	if(isset($username) && isset($password)){

  		if(Auth::attempt(['username'=> $username, 'password' => $password])){
  			$json['is_successful'] 	= true;
        $json['message']        = "Welcome " . ucfirst(Auth::user()->name) . "!";
        if (Auth::user()->hasRole(['owner', 'admin'])) {
          $json['redirect_url'] 	= route('dashboard');
        } else {
          $json['redirect_url'] 	= route('sales');
        }
  		} else {
  			$json['is_successful'] = false;
  			$json['message']	   = "Username / Password is incorrect.";
  		}
  	} else {
  		$json['is_successful']  = false;
  		$json['message']		= "Please enter username and password.";
  	}

  	return response()->json($json);
  }

  public function logout() {
    if (Auth::check()) {
      Auth::logout();
      return redirect()->route('login');
    }
  }
}
