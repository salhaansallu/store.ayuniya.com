<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }

    protected $fillable = [
        'sub_category_name',
        'category_id',
    ];
}
