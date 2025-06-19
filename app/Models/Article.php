<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'content',
        'cover',
        'status',
        'category_id',
        'tag_id',
        'meta',
    ];

    public function tags()
    {
        return $this->belongsToMany(\App\Models\Tag::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function tag()
    {
        return $this->belongsToMany(Tag::class);
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

    public function getFirstImageUrlAttribute(): ?string
    {
        if (preg_match('/<img[^>]+src="([^">]+)"/', $this->content, $matches)) {
            return $matches[1];
        }

        return null;
    }
    
    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover
            ? Storage::url($this->cover)
            : null;
    }
}
