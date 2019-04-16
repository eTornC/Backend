<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{
    public $table = 'buckets';

    public $fillable = [
      'id', 'hour_start', 'hour_final', 'quantity', 'filled'
    ];

    public $casts = [
      'filled' => 'boolean'
    ];

    public $timestamps = true;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function queue()
    {
        return $this->belongsTo('eTorn\Models\Queue', 'id_queue');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function turns()
    {
        return $this->hasMany('eTorn\Models\Turn', 'id_bucket');
    }

}