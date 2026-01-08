<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DaftraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;


class AuthController extends Controller
{

    public function index()
    {
        return view('web.auth.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth('web')->attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::guard('web')->check()) {
                // المستخدم مسجل دخول بنجاح
                return redirect()->route('name');
            } else {
                return '1';
            }
        }

        toastr()->error(__('The_provided_credentials_do_not_match_our_records'));

        return redirect()->back();

    }

    public function logout(Request $request)
    {
        Auth('user')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }

    public function getRegister()
    {
        return view('web.auth.register');
    }

    public function register(Request $request)
    {


        $rules = [
            'name' => 'required|string|max:36',
            'email' => 'required|unique:users,email',
            'password' => 'required|string|min:8',
            'type' => 'required|int|in:1,2,3',

        ];
        $request->merge([
            'name' => $request->first . ' ' . $request->last
        ]);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errorMessage = implode('<br>', $validator->errors()->all());

            toastr()->error($errorMessage);

            return redirect()->back();

        }
        $request->merge([
            'password' => Hash::make($request->password),
        ]);
        $dafter = new DaftraService();
        $data = [
            'Client' => [
                "first_name" => $request->first,
                "last_name" => $request->last,
                "email" => $request->email,
                "password" => $request->password,
                "type" => $request->type,
            ]
        ];

       $createClient= $dafter->createClient($data);
       $request->merge([
           'client_number'=>$createClient['id']
       ]);
        User::query()->create($request->only('email', 'password', 'name', 'type','client_number'));
        return redirect()->route('user.login');


    }

    public function forgetPasswordGet()
    {
        return view('web.auth.forget');

    }

    public function forgetPasswordPost(Request $request)
    {
        $rules = [
            'email' => 'required|exists:users,email',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errorMessage = implode('<br>', $validator->errors()->all());

            toastr()->error($errorMessage);

            return redirect()->back();

        }
//        $code = rand(1000, 9999);
        $code = '1111';
//        Mail::to($request->email)->send(new ForgetMail($code));
        $user = User::query()->where('email', $request->email)->update([
            'code' => $code
        ]);
        return redirect()->route('user.code', ['email' => $request->email]);
    }

    public function code($email)
    {
        return view('web.auth.code', compact('email'));
    }

    public function cheak(Request $request)
    {
        $code = $request->code_1 . $request->code_2 . $request->code_3 . $request->code_4;
        $user = User::query()->where('email', $request->email)->firstOrFail();
        if ($code == $user->code) {
            return redirect()->route('user.returnpassword', ['email' => $user->email]);
        } else {
            toastr()->error('الرمز غير صحيح');

            return back();
        }

    }

    public function returnpassword($email)
    {
        return view('web.auth.return', compact('email'));
    }

    public function changePassword(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errorMessage = implode('<br>', $validator->errors()->all());

            toastr()->error($errorMessage);

            return redirect()->back();

        }
        $request->merge([
            'password' => Hash::make($request->password),
        ]);
        $user = User::query()->where('email', $request->email)->first();
        $user->update([
            'password' => $request->password
        ]);
        return redirect()->route('user.login');
    }

}
