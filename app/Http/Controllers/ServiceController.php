<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceFullResource;
use App\Http\Resources\ServiceLightResource;
use App\Models\Service;
use App\Models\ServicePhoto;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function create(Request $request)
    {
        $user = auth()->user();
        if ($user->role_id !== 1){
            return response()->error("You don't have permissions!", 403);
        }

        $data = $request->all();

        $v = validator($data, [
            'images.*'=>'required|file|mimes:jpeg,jpg,png',
            'images'=>'required|array|min:1|max:4',
            'title'=>'required|string',
            'description'=>'required|string',
            'date_from'=>'required|integer',
            'date_to'=>'required|integer',
            'price'=>'required|integer',
            'tags' => 'array',
            'tags.*'=>'string',
        ]);

        if($v->fails()) {
            return response()->error($v->errors(), 422);
        }
        $service = Service::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'price'=>$request->price,
            'user_id'=>$user->getAuthIdentifier(),
            'date_from' => ((int) $request->date_from),
            'date_to' => ((int) $request->date_to),
        ]);

        $tagsIds = [];
        foreach(request('tags') as $tagName){
            $tag = Tag::create(['name' => $tagName]);
            $tagsIds[] = $tag->id;
        }

        $service->tags()->attach($tagsIds);


        foreach ($request->images as $photo){
            $name = Str::random(10);
            $filename = $name . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('uploads'), $filename);
            ServicePhoto::create([
                'service_id'=>$service->id,
                'photo'=> '/public/uploads/' . $filename
            ]);
        }

        return response()->json([
            'images'=>$service->photos->pluck('photo'),
            'description'=>$service->description,
            'price'=>$service->price,
            'user_id'=>$service->user_id,
            'date_from' => $service->date_from,
            'date_to' => $service->date_to,
            'tags'=>$service->tags->pluck('name')
        ], 201);
    }

    public function index()
    {
        return response()->json(ServiceLightResource::collection(Service::all()));
    }

    public function authIndex()
    {
        return response()->json(ServiceLightResource::collection(auth()->user()->services));
    }

    public function show(Service $service)
    {
        return response()->json(new ServiceFullResource($service));
    }

    public function getFreelancer(Service $service)
    {

        return response()->json(collect($service->user)->merge(['avatar' => $service->user->profile->avatar]));
    }
}
