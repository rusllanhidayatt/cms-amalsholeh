<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'content',
        'cover',
        'category_id',
        'tag_id',
        'meta',
    ];

    public function article()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    
    public function tag()
    {
        return $this->belongsToMany(tag::class);
    }
    
    public function metas()
    {
        return $this->hasMany(PostMeta::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function meta()
    {
        return $this->hasMany(PostMeta::class, 'article_id');
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    
}
