<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class voucher extends Model
{
    use HasFactory;
    protected $fillable = [
        "transaction_number",
        "price",
        "service_id",
        "confirmed"

    ];


    public function Banck()
    {
        return $this->belongsTo(Bank::class);
    }

    public function Service()
    {
        return $this->belongsTo(Service::class);
    }
}
