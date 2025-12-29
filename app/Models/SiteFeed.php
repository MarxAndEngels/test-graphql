<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiteFeed extends Model
{
    // Так как таблица называется site_feed (в единственном числе), 
    // Laravel может искать site_feeds. Укажем её явно:
    protected $table = 'site_feed';

    protected $fillable = ['site_id', 'feed_id'];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }
}