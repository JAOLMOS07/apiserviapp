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
        'client_id',
        'calification',
        'active'
    ];

    public function Workers()
    {
        return $this->belongsToMany(Worker::class);
    }
    public function Client()
    {
        return $this->belongsTo(Client::class);
    }

    public function Categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function Contract()
    {
        return $this->HasMany(Contract::class);
    }
    public function Rate()
    {
        return $this->hasOne(Rate::class);
    }
}
