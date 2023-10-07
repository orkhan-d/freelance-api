<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->all();
        $v = Validator::make($data, [
            'surname'=>'required|min:2|regex:/^[а-яА-Я]+$/u',
            'name'=>'required|min:2|regex:/^[а-яА-Я]+$/u',
            'email'=>'required|email|unique:users,email',
            'password'=>[
                'required',
                'string',
                'min:6',
                'regex:/^[a-zA-Z0-9]+$/u',
                Password::min(6)->numbers()
            ],
            'role_id'=>'required|integer|exists:roles,id',
        ]);

        if($v->fails()) {
            return response([
                'error'=>[
                    'message'=>$v->errors()
                ]
            ], 422);
        }
        $uuid = Str::uuid();
        $user = User::create($data + ['token'=>$uuid]);
        return response([
            'token'=>$uuid
        ], 201);
    }
}
