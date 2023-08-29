<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = [
        'rate_service',
        'rate_worker',
        'service_id',
        'worker_id'
    ];
    public function Worker()
    {
        return $this->belongsTo(Worker::class);
    }
    public function Service()
    {
        return $this->belongsTo(Service::class);
    }
}
