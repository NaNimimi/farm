<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    protected $fillable = ['amount', 'customer_id', 'doctor_id', 'service_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

