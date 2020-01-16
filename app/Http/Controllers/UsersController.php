<?php

namespace App\Http\Controllers;

use App\Image;
use App\Notifications\UserRegistration;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function registrate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'password' => 'required|min:6',
        ]);
        $success = false;


        if (!$validation->fails()) {
            User::createOne($request);
            $success = true;
        }

        return $this->success($success);
    }

    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email'
        ]);
        $success = false;

        if (!$validation->fails()) {
            $success = (boolean)$request->user()->update($request->only(['first_name', 'last_name', 'email']));
        }

        return $this->success($success);
    }

    public function acceptRegistration(Request $request)
    {
        User::where('registration_token', $request->token)->update([
            'active' => 1, 'reg',
            'registration_token' => null
        ]);

        return response()->redirect('/');
    }

    public function login(Request $request)
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = $request->user();
            $authToken = $user->createToken('Auth Token');

            if ($request->remember_me) {
                $token = $authToken->token;
                $token->expires_at = Carbon::now()->addYear();
                $token->save();
            }

            return [
                'success' => true,
                'access_token' => $authToken->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($authToken->token->expires_at)->toDateTime()
            ];
        }

        return $this->success(false);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->success(true);
    }
}
