<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    public function login(){
        return view('dashboard.auth.login');
    }
    public function postlogin(AdminLoginRequest $request){
        $remember_me = $request->has('remember_me') ? true : false;
        if(auth()->guard('admin')->attempt(
            [
                'email' =>$request->input('email'),
                'password' =>$request->input('password')
            ],$remember_me
        )){
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->back()->with('erorr' , 'حناك خطأ في البيانات');
    }

    public function logout(){
        /*
         auth('admin')->logout();
         return redirect()->route('admin.login');
         */
        $gaurd=  $this->logGaurd();
        $gaurd->logout();
        return redirect()->route('admin.login');
    }
    protected function logGaurd(){
        return auth('admin');
    }
}
