<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->changeLang(\request('lang'));
    }

    public function register(Request $request)
    {
        try {
            $rules = [
                "email" => "required|email|string|max:191|unique:users",
                "password" => "required|min:6",
                "c_password" => "required|same:password|min:6",
                "name" => "required|string|max:191"
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError($validator->errors(), 400);
            }
            $data = $request->only([
                'name',
                'password',
                'c_password',
                'email'
            ]);
            $data['password'] = Hash::make($data['password']);
            $data['image'] = $this->uplaodImagePublicFoleder($request);
            $user = User::query()->create($data);
            $token = $user->createToken($user->name . ' Token')->accessToken;
            return $this->sendToken($token);
        } catch (\Exception $exception) {

            return $this->sendError($exception->getMessage(), 500);
        }

    }

    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email|string|max:191',
                'password' => 'required',
            ];
            $credentials = $request->only('email', 'password');
            $validator = Validator::make($credentials, $rules);
            if ($validator->fails()) {
                return $this->sendError($validator->errors(), 400);
            }

            if (!$user = Auth::attempt($credentials)) {

                return $this->sendError(__('auth.failed'), 401);
            }

            $user = $request->user();
            $token = $user->createToken($user->name . ' Token')->accessToken;
            return $this->sendToken($token, 200);
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage() . $ex->getLine(), 500);
        }
    }

    public function details()
    {
        try {
            $user = \request()->user();
            return $this->sendResponseWithoutMessage($user, 200);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }

    }
}
