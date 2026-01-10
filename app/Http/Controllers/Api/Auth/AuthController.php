<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'gender' => 'required|in:1,2',
            'date_of_birth' => 'required|date',
            'password' => 'required|min:6|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }


        $user = User::query()->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'gender' => $request['gender'],
            'date_of_birth' => $request['date_of_birth'],
            'password' => Hash::make($request['password']),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;
        $user->setAttribute('token', $token);

        return mainResponse(true,__('ok'), compact('user'), [], 200);
    }

    public function login(Request $request)
    {

        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return mainResponse(false, $validator->errors()->first(), [], $validator->errors()->messages(), 101);
        }
        $user = User::query()->where('email', $request['email'])->first();

        if (! $user || ! Hash::check($request['password'], $user->password)) {
            return  mainResponse(false, __('هناك خطا في البيانات'), [], [], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;
        $user->setAttribute('token', $token);

        return  mainResponse(true,__('ok'), compact('user'), [], 200);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return  mainResponse(true,__('Logged out'), [], [], 300);    }


}
