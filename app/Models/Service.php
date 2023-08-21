<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    public function Worker()
    {
        return $this->belongsTo(Worker::class);
    }
    public function Client()
    {
        return $this->belongsTo(Client::class);
    }
}
