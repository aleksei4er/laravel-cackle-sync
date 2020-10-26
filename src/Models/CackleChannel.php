<?php

namespace Aleksei4er\LaravelCackleSync\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CackleChannel extends Model
{
    protected $guarded = ['id'];
    
    /**
     * Comments relation
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(CackleComment::class, 'channel_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CackleReview::class, 'channel_id');
    }

}
