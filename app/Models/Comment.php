<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id', 
        'parent_id', 
        'content', 
        'name', 
        'email'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
