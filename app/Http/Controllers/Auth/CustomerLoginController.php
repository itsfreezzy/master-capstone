<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Customer;
use App\Mail\ForgotPassword;

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
            if ($request->session()->get('url')['intended'] == null || $request->session()->get('url')['intended'] == '') {
                return redirect()->route('client.index');
            } else if (strpos( $request->session()->get('url')['intended'], 'logout' )) {
                return redirect()->route('client.index');
            } else if (strpos( $request->session()->get('url')['intended'], 'admin' )) {
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

    public function forgotPasswordForm() {
        return view('auth.forgot-pw');
    }

    public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('client.forgot-password')
                    ->withInput()
                    ->withErrors($validator);
        }

        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return redirect()->route('client.forgot-password')->withInput()->with(['error' => 'E-mail does not belong to any customer. Please try again.']);
        }

        $newpass = $this->generateRandomPassword();
        $customer->password = Hash::make($newpass);
        $customer->save();

        \Mail::to($customer->email)->send(new ForgotPassword($customer, $newpass));
        return redirect()->route('client.login')->with(['success' => 'Email regarding password change sent. Please wait for it.']);
    }

    private function generateRandomPassword($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
