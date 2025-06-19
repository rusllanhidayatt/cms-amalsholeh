<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'description',
    ];

    public function articles()
    {
        return $this->belongsToMany(\App\Models\Article::class);
    }
    
    public function article()
    {
        return $this->belongsToMany(Article::class);
    }
}