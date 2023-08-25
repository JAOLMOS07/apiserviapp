<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    public function Workers()
    {
        return $this->belongsToMany(Worker::class);
    }
    public function Services()
    {
        return $this->belongsToMany(Service::class);
    }
}
