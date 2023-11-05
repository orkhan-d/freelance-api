<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = false;

    public function tags()
    {
        return $this->BelongsToMany(tag::class, 'service_tags', 'service_id', 'tag_id');
    }

    public function photos()
    {
        return $this->hasMany(ServicePhoto::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
