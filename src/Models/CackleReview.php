<?php

namespace Aleksei4er\LaravelCackleSync\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CackleReview extends Model
{
    /**
     * Channel relation
     *
     * @return BelongsTo
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(CackleChannel::class, 'channel_id');
    }
}
