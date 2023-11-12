<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderLightResource;
use App\Http\Resources\ServiceLightResource;
use App\Models\Order;
use App\Models\Tag;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(OrderLightResource::collection(Order::all()));
    }

    public function authIndex()
    {
        return response()->json(OrderLightResource::collection(auth()->user()->orders));
    }

    public function store()
    {
        $data = request()->all();

        $v = validator($data, [
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
        $order = Order::create([
            'title'=>$data['title'],
            'description'=>$data['description'],
            'price'=>$data['price'],
            'user_id'=>auth()->user()->id,
            'date_from' => ((int) $data['date_from']),
            'date_to' => ((int) $data['date_to']),
        ]);

        $tagsIds = [];
        foreach(request('tags') as $tagName){
            $tag = Tag::create(['name' => $tagName]);
            $tagsIds[] = $tag->id;
        }

        $order->tags()->attach($tagsIds);

        return response()->json(new OrderLightResource($order), 201);
    }
}
