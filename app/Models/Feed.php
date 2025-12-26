<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feed extends Model
{
    use HasFactory;

   // Добавьте этот массив:
    protected $fillable = [
        'name',
        'type',
        'url',
    ];

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'site_feed');
    }
    
}