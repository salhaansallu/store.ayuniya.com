<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address1',
        'address2',
        'province',
        'district',
        'city',
        'type',
        'user_id',
    ];
}
