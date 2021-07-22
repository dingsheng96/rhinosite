<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\City;
use App\Models\Country;
use App\Models\CountryState;
use App\Rules\PasswordFormat;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'role'              =>  ['required', 'in:member,merchant'],
            'name'              =>  ['required', 'string', 'max:255'],
            'email'             =>  ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'          =>  ['required', 'string', new PasswordFormat, 'confirmed'],
            'address_1'         =>  ['required', 'min:3', 'max:255'],
            'address_2'         =>  ['nullable'],
            'country'           =>  ['required', 'exists:' . Country::class . ',id'],
            'postcode'          =>  ['required', 'digits:5'],
            'country_state'     =>  [
                'required',
                Rule::exists(CountryState::class, 'id')
                    ->where('country_id', $this->get('country'))
            ],
            'city'          =>  [
                'required',
                Rule::exists(City::class, 'id')
                    ->where('country_state_id', $this->get('country_state'))
            ]
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
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
