<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'article_id', 
        'key', 
        'value'
    ];
    
    public function article()
    {
        return $this->belongsToMany(Tag::class);
    }  
}
