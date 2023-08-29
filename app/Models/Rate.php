<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = [
        'rate_client',
        'rate_worker',
        'comment_client',
        'comment_worker',
        'service_id',
        'worker_id',
        'client_id'

    ];
    public function Worker()
    {
        return $this->belongsTo(Worker::class);
    }
    public function Client()
    {
        return $this->belongsTo(Client::class);
    }
}
