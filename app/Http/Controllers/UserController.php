<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthUserInfo;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Dimensions;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpKernel\Profiler\Profile;

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
        $profile = UserProfile::create(['id'=>$user->id]);
        return response([
            'token'=>$uuid
        ], 201);
    }

    public function login(Request $request)
    {
        $v = Validator::make($request->all(), [
            'email'=>'required|email',
            'password'=>'required|string|min:6'
        ]);
        if($v->fails()) {
            return response([
                'error'=>[
                    'message'=>$v->errors()
                ]
            ], 422);
        }
        $user = User::firstWhere('email', $request->get('email'));
        if(is_null($user)){
            return response([
                'error'=>[
                    'code'=>404,
                    'message'=>'Not found!'
                ]
            ], 404);
        }
        if(!$user->firstWhere('password', $request->get('password'))){
            return response([
                'error'=>[
                    'code'=>401,
                    'message'=>'Not Authorized!'
                ]
            ], 401);
        }

        $uuid = Str::uuid();
        $user->token = $uuid;
        $user->update([
            'token'=>$uuid
        ]);
        //DB::commit();
        return response(
            ['token'=>$uuid],
        );
    }

    public function get(Request $request)
    {
        $user = auth()->user();
        return response()->json(AuthUserInfo::make($user));
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if(is_null($token)){
            return response([
                'error'=>[
                    'code'=>401,
                    'message'=>'Not Authorized!'
                ]
            ], 401);
        }
        $user = User::where('token', $token)->first();

        if(is_null($user)){
            return response([
                'error'=>[
                    'code'=>401,
                    'message'=>'Not Authorized!'
                ]
            ], 401);
        }
        else{
            $user->update([
                "token"=>null
            ]);
            return response(null);
        }
    }


}
