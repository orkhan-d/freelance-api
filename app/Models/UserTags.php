<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTags extends Model
{
    protected $guarded = false;

    public function get_role_id()
    {
        return $this->role_id;
    }
}
