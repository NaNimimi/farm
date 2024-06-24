<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'doctor_id', 'service_id', 'price', 'doctor_amount', 'cashier_amount'];

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

    public static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            if (is_null($order->price) && $order->service && $order->service->price) {
                $order->price = $order->service->price;
            }

            $halfPrice = intval($order->price / 2);

            $order->doctor_amount = $halfPrice;
            $order->cashier_amount = $halfPrice;

            $order->doctor->savedM += $halfPrice;
            $order->doctor->save();

            $moneyRecord = Money::firstOrNew([
                'customer_id' => $order->customer_id,
                'doctor_id' => $order->doctor_id,
                'service_id' => $order->service_id,
            ]);
            $moneyRecord->price += $halfPrice;
            $moneyRecord->save();
        });
    }
}
