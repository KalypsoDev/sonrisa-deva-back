<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =  ['customer_id', 'total_quantity', 'total_price', 'requested_date'];

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }

    public function ordersProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
