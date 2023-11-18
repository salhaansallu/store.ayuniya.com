<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MainOrders extends Model
{
    use HasFactory;

    public function orders()
    {
        return $this->hasMany(Orders::class, 'order_number', 'order_number');
    }

    protected $fillable = [
        'order_number',
        'user_id',
        'bill_address',
        'ship_address',
        'status',
        'print',
        'delivery_charge',
        'total_order',
        'courier name',
        'hand over date',
        'track code',
        'track link'
    ];
}
