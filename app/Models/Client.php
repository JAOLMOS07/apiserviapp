<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id'
    ];
    public function User()
    {
        return $this->belongsTo(User::class);


    }
    public function Services()
    {
        return $this->hasMany(Service::class);
    }

    public function Categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
