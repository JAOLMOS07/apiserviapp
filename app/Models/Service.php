<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'Date',
        'worker_id',
        'client_id',
        'calification',
        'active'
    ];

    public function Worker()
    {
        return $this->belongsTo(Worker::class);
    }
    public function Client()
    {
        return $this->belongsTo(Client::class);
    }

    public function Categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
