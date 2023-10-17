<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceReview;
use Illuminate\Http\Request;
use Validator;

class ServiceReviewController extends Controller
{
    public function create(Service $service, Request $request)
    {
        $user = auth()->user();
        if ($user->role_id!=0){
            return response()->error("You don't have permissions!", 403);
        }

        $data = $request->all();
        $v = validator($data, [
            'message'=>'required|string|min:3',
            'stars'=>'required|gte:1.0|lte:5.0'
        ]);

        if($v->fails()) {
            return response()->error($v->errors(), 422);
        }

        $review = ServiceReview::create([
            'service_id'=>$service->id,
            'user_id'=>$user->getAuthIdentifier(),
            'message'=>$request->message,
            'stars'=>$request->stars
        ]);

        return response()->json([
            'message'=>$review->message,
            'stars'=>$review->stars,
        ], 201);
    }
}
