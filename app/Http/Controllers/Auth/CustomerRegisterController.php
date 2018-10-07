<?php

namespace App\Http\Controllers\Auth;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Auth;
use Anam\Captcha\Captcha;
use Illuminate\Http\Request;
use App\Mail\Welcome;

class CustomerRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/customer';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:customer');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|regex:/^[\pL\s]+$/u|max:150|unique:tblcustomers,name',
            'username' => 'required|string|alpha_dash|max:50|unique:tblcustomers,username',
            'email' => 'required|string|email|max:191|unique:tblcustomers,email',
            'type' => 'required|',
            'password' => 'required|string|min:5|confirmed',
            'tinnumber' => 'required|unique:tblcustomers,tinnumber',
            'contactnumber' => 'required|unique:tblcustomers,contactnumber|digits:11',
            'consent' => 'required|accepted',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $customer = Customer::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'type' => $data['type'],
            'tinnumber' => $data['tinnumber'],
            'contactnumber' => $data['contactnumber'],
        ]);

        $customer->code = sprintf('CUST-%04d', $customer->id);
        $customer->save();

        \Mail::to($customer->email)->send(new Welcome($customer));
        
        return $customer;
    }

    public function showRegisterForm() {
        return view('auth.customer-register');
    }

    protected function guard() {
        return Auth::guard('customer');
    }

    public function register(Request $request, Captcha $captcha)
    {
        $response = $captcha->check($request);
        if (! $response->isVerified()) {
            return redirect()->route('client.register')->with(['error' => 'Error verifying captcha. Please try again.']);
        }

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect()->route('client.register')
                        ->withErrors($validator)
                        ->withInput();
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
}
