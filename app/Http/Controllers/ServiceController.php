<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthUserInfo;
use App\Models\Service;
use App\Models\ServicePhoto;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id!=1){
            return response()->error("You don't have permissions!", 403);
        }

        $data = $request->all();

        $v = validator($data, [
            'images.*'=>'required|file|mimes:jpeg,jpg,png',
            'images'=>'required|array|min:1|max:4',
            'title'=>'required|string',
            'description'=>'required|string',
            'datesCount'=>'required|integer',
            'price'=>'required|integer',
            'tags.*'=>'integer|exists:tags,id',
        ]);

        if($v->fails()){
            return response()->error($v->errors(), 422);
        }

        $service = Service::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'datesCount'=>$request->datesCount,
            'price'=>$request->price,
            'user_id'=>$user->getAuthIdentifier(),
        ]);

        foreach ($request->images as $photo){
            $s = Str::random(10);
            $filename = $s . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads'), $filename);
            ServicePhoto::create([
                'service_id'=>$service->id,
                'photo'=> 'uploads/' . $filename
            ]);
        }

        if($request->tags){
            $service->tags()->sync($request->tags);
        }

        return response()->json([
            'images'=>$service->photos->pluck('photo'),
            'description'=>$service->description,
            'datesCount'=>$service->datesCount,
            'price'=>$service->price,
            'user_id'=>$service->user_id,
            'tags'=>$service->tags->pluck('id')
        ], 201);
    }
}
