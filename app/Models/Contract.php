<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'date_init',
        'hours_worked',
        'description',
        'date_end',
        'service_id',
        'worker_id',
        'signed'
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
