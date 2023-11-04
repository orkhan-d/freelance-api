<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $guarded = false;

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'profile_tags', 'profile_id', 'tag_id');
    }
}
