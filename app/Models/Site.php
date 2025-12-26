<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Добавьте эту строку
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Site extends Model
{
    use HasFactory;
    protected $table = 'sites';
    protected $guarded = [];
    public $timestamps = true;

    protected $fillable = [
        'favicon_image',
        'dealer_id',
        'user_id',
    ];

    public function dealer(){
        return $this->belongsTo(Dealer::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
