<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'varients'; 

    public static function getLeastAvailableStock()
    {
        return self::orderBy('qty', 'asc')->first();
    }


}
