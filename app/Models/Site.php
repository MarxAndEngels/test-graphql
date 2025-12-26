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

    // protected $fillable = [
    // 'url', 
    //     'favicon_image', 
    //     'parent_id', 
    //     'is_main', 
    //     'is_active', 
    //     'dealer_id'
    // ];

    // Получить главный сайт (родителя)
    public function parent()
    {
        return $this->belongsTo(Site::class, 'parent_id');
    }
    /**
     * Получить все зеркала этого сайта.
     */
    public function children()
    {
        return $this->hasMany(Site::class, 'parent_id');
    }
    public function dealer(){
        return $this->belongsTo(Dealer::class);
    }
    public function feeds()
    {
        // Явно указываем 'site_feed' как имя таблицы
        return $this->belongsToMany(Feed::class, 'site_feed')->withTimestamps();
    }   
}
