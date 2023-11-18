<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_appointments extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_date',
        'user_id',
        'disease',
    ];
}
