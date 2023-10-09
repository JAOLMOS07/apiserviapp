<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'calification'
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function Rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function Categories()
    {
        return $this->belongsToMany(Category::class);
    }

}
