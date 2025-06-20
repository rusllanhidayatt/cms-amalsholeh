<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'event',
        'user_agent',
        'ip',
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];
}
