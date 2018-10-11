<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

class CustomerLoginController extends Controller
{   
    use AuthenticatesUsers;

    protected function guard() 
    {
        return Auth::guard('customer');
    }

    public function __construct()
    {
        $this->middleware('guest:customer', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        return view('auth.customer-login');
    }

    public function login(Request $request)
    {
        // dd($request->session()->get('url')['intended']);
        // Validate the form data
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);
        
        // Attemt to log the user in
        if (Auth::guard('customer')->attempt(['username' => $request->username, 'password' => $request->password])) {
            // If successful, then redirect to their intended location
            if (strpos( $request->session()->get('url')['intended'], 'logout' )) {
                return redirect()->route('client.index');
            } 
            return redirect()->intended($request->session()->get('url')['intended']);
        }

        // If unsuccessful, then redirect back to login with the form data
        return redirect()->back()->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        // $request->session()->invalidate();

        return redirect()->guest('/');
    }

    protected $redirectTo = '';
}
