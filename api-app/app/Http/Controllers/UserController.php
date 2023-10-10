<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthUserInfo;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Dimensions;
use Illuminate\Validation\Rules\File;
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

    public function fill(User $user, Request $request)
    {
        $contacts = request('contacts');
        if(!is_null($contacts))
            for ($i = 0; $i < count($contacts); $i++) {
                $v = \validator(request()->all(), [
                    "contacts.$i.link"=>'url:http,https',
                    "contacts.$i.name"=>'string',
                ]);
                if($v->fails()){
                    return response()->error($v->errors(), 422);
                }
            }


        $v = validator(request()->all(), [
            "avatar" => 'file|max:1024|mimes:jpeg,jpg,png|dimensions:max_width=300,max_height=300',
            "description"=>'string',
            'experience'=>'integer',
            'age'=>'integer',
        ]);

        if($v->fails()){
            return response()->error($v->errors(), 422);
        }

        if (!is_null($request->file('avatar'))){
            $s = Str::random(10);
            $filename = $s . $request->file('avatar')->getExtension();
            $request->file('avatar')->move(base_path('uploads'), $filename);
        }

        $auser = auth()->user();
        if (is_null($auser) || $user->id!=$auser->id){
            response()->error("You are not have permission", 403);
        }
        $user->update([
            'description'=>$request->description,
            'avatar'=>$request->avatar,
            'experience'=>$request->experience,
            'age'=>$request->age,
        ]);
        return response()->json([
            'status'=>'success'
        ], 200);
    }
}
