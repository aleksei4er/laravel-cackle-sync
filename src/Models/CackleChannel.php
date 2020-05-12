<?php

namespace Aleksei4er\LaravelCackleSync\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CackleChannel extends Model
{
    /**
     * Comments relation
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(CackleComment::class, 'channel_id');
    }

}
