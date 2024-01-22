<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'blog_image',
        'blog_Title',
        'blog_dis',
        'blog_content'

    ];
}
