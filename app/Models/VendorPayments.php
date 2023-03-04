<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayments extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'status',
        'vendor_id',
    ];
}
