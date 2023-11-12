<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'order_tags', 'order_id', 'tag_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'order_id');
    }
}
