<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReview extends Model
{
    protected $guarded = false;

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'service_id');
    }
}
