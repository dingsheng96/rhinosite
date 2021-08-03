<?php

namespace App\Http\Controllers\Auth;

use App\Models\City;
use App\Models\Role;
use App\Models\User;
use App\Models\Country;
use App\Rules\PhoneFormat;
use App\Models\CountryState;
use Illuminate\Http\Request;
use App\Rules\PasswordFormat;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
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
            'role'          =>  ['required', 'in:member,merchant'],
            'name'          =>  ['required', 'string', 'max:255', Rule::unique(User::class, 'name')->whereNull('deleted_at')],
            'email'         =>  ['required', 'string', 'email', 'max:255', Rule::unique(User::class, 'email')->whereNull('deleted_at')],
            'phone'         =>  ['required', 'string', new PhoneFormat],
            'password'      =>  ['required', 'string', new PasswordFormat, 'confirmed'],
            'address_1'     =>  ['required', 'string', 'min:3', 'max:255'],
            'address_2'     =>  ['nullable', 'string', 'min:3', 'max:255'],
            'country'       =>  ['required', 'exists:' . Country::class . ',id'],
            'postcode'      =>  ['required', 'digits:5'],
            'country_state' =>  ['required', Rule::exists(CountryState::class, 'id')->where('country_id', $data['country'])],
            'city'          =>  ['required', Rule::exists(City::class, 'id')->where('country_state_id', $data['country_state'] ?? null)],
            'agree'         =>  ['required', 'accepted']
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
        $roles = [
            'member' => Role::ROLE_MEMBER,
            'merchant' => Role::ROLE_MERCHANT
        ];

        DB::beginTransaction();

        try {

            $user = User::create([
                'name'      =>  $data['name'],
                'email'     =>  $data['email'],
                'phone'     =>  $data['phone'],
                'password'  =>  Hash::make($data['password']),
                'status'    =>  User::STATUS_ACTIVE
            ]);

            $user->address()->create([
                'address_1' => $data['address_1'],
                'address_2' => $data['address_2'],
                'postcode'  => $data['postcode'],
                'city_id'   => $data['city']
            ]);

            $user->assignRole($roles[$data['role']]);

            DB::commit();

            return $user;
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            return redirect()->back()->with('fail', __('messages.contact_support'));
        }
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        if (empty(request()->get('role'))) {
            return redirect()->route('register', ['role' => 'member']);
        }

        abort_unless(
            request()->get('role') == strtolower(Role::ROLE_MEMBER) || request()->get('role') == strtolower(Role::ROLE_MERCHANT),
            404
        );

        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return redirect(route('login') . '#' . $request->get('role'))->withSuccess(__('messages.register_success'));
    }
}
