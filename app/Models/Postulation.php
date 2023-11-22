<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulation extends Model
{

    use HasFactory;
    protected $fillable = [
        "worker_id",
        "service_id",
        "price"
    ] ;
    public function Worker()
    {
        return $this->belongsTo(Worker::class);
    }
    public function Service()
    {
        return $this->belongsTo(Service::class);
    }

}
