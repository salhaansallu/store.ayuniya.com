<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Orders extends Model
{
    use HasFactory;

    public function MainOrders()
    {
        return $this->belongsTo(MainOrders::class, 'order_number', 'order_number');
    }
    protected $fillable = [
        'order_number',
        'product_id',
        'qty',
        'user_id',
        'total',
    ];
}
