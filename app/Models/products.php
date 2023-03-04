<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class products extends Model
{
    use HasFactory;

    public function varient()
    {
        return $this->hasMany(varients::class, 'pro_id', 'id')->where('status', 'active');
    }

    public function sortedvarients(){
        return $this->hasMany(varients::class, 'pro_id', 'id')->where('status', 'active')->where("sales_price", "<=", Session::get("max_price_range"));
    }

    protected $fillable = [
        'product_name',
        'short_des',
        'long_des',
        'category',
        'banner',
    ];
}
