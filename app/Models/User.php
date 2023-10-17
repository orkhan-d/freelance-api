<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $guarded = false;
    public $timestamps = true;

    public function tags()
    {
        return $this->BelongsToMany(tags::class, 'usertags', 'user_id', 'tag_id');
    }
}