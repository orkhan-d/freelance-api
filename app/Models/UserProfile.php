<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $guarded = false;

    public function tags()
    {
        return $this->BelongsToMany(tags::class, 'usertags', 'user_id', 'tag_id');
    }
}
