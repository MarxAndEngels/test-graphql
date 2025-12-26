<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
   use HasFactory;
   protected $table = 'dealers';
   protected $guarded = false;
   public $timestamps = true;

   public function user(){
    return $this->belongsTo(User::class);
  }
  public function sites(): \Illuminate\Database\Eloquent\Relations\HasMany
{
    return $this->hasMany(Site::class);
}
}
