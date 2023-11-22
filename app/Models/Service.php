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
        'price_min',
        'price_max',
        'Date',
        'client_id',
        'worker_id',
        'calification',
        'status',
        'confirmed'
    ];

    public function Workers()
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


    public function Voucher()
    {
        return $this->hasOne(Voucher::class);
    }

    public function Report()
    {
        return $this->hasMany(Report::class);
    }

}
