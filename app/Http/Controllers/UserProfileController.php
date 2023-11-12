<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserProfileResource;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserProfileController extends Controller
{
    public function fill(Request $request)
    {
        $data = $request->all();
        $v = validator($data, [
            "description"=>'nullable|string',
            'tags' => 'array',
            'tags.*'=>'nullable|string'
        ]);

        if ($v->fails()) {
            return response()->error($v->errors(), 422);
        }

        $profile = auth()->user()->profile;

        $profile->update([
            'description'=>$data['description'],
        ]);

        $tagsIds = [];
        foreach(request('tags') as $tagName) {
            $tag= Tag::create(['name' => $tagName]);
            $tagsIds[] = $tag->id;
        }

        $profile->tags()->sync($tagsIds);

        return response()->json([
            'status'=>'success'
        ]);
    }

    public function updateAvatar()
    {
        $data = request()->all();

        $v = validator($data, [
            "avatar" => 'nullable|file|max:1024|mimes:jpeg,jpg,png',
        ]);

        if ($v->fails()) {
            return response()->error($v->errors(), 422);
        }

        $user = auth()->user();

        $url = '';

        if (!is_null(request()->file('avatar'))) {
            $s = Str::random(10);
            $filename = $s . '.' .request()->file('avatar')->getClientOriginalExtension();
            request()->file('avatar')->move(public_path('uploads'), $filename);
            $url = '/public/uploads/' . $filename;
            $user->profile->update([
                'avatar' => $url
            ]);
        }

        return response()->json([
            'url' => $url,
        ]);
    }

    public function get(User $user)
    {
        return response()->json(UserProfileResource::make($user));
    }
}
