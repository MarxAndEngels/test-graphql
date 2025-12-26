<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class City extends Model
{
    use HasFactory;
     protected $table = 'cities';
   protected $guarded = false;
   public $timestamps = true;

   public function dealers()
    {
        return $this->hasMany(Dealer::class);
    }
}
