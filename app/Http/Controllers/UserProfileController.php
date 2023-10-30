<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    public function fill(UserProfile $user, Request $request)
    {
        $data = $request->all();

        $v = validator($data, [
            "avatar" => 'file|max:1024|mimes:jpeg,jpg,png|dimensions:max_width=1200,max_height=1200',
            "description"=>'string',
            'tags.*'=>'string'
        ]);

        if ($v->fails()){
            return response()->error($v->errors(), 422);
        }

        $auser = auth()->user();
        if (is_null($auser) || $user->id!=$auser->id){
            response()->error("You are not have permission", 403);
        }

        if (!is_null($request->file('avatar'))){
            $s = Str::random(10);
            $filename = $s . '.' .$request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path('uploads'), $filename);
            $user->update([
                'avatar'=>'public/uploads/' . $filename
            ]);
        }

        $user->update([
            'description'=>$request['description'],
        ]);
        $user->tags()->sync($request['tags']);

        return response()->json([
            'status'=>'success'
        ], 200);
    }

    public function get(UserProfile $user)
    {
        return response()->json(UserProfileResource::make($user));
    }
}
