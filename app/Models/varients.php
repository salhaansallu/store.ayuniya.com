<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class varients extends Model
{
    use HasFactory;
    
    public function products()
    {
        return $this->belongsTo(products::class, 'pro_id', 'id');
    }
    
    protected $fillable = [
        'sku',
        'v_name',
        'unit',
        'qty',
        'price',
        'sales_price',
        'weight',
        'status',
        'image_path',
        'pro_id',
    ];
}
