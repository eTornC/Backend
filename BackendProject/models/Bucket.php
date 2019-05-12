<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsToAlias;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Bucket
 * @package eTorn\Models
 * @property int $id
 * @property string $hour_start
 * @property string $hour_final
 * @property int $quantity
 * @property boolean $filled
 */
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
     * @return BelongsToAlias
     */
    public function queue()
    {
        return $this->belongsTo('eTorn\Models\Queue', 'id_queue');
    }

    /**
     * @return HasMany
     */
    public function turns()
    {
        return $this->hasMany('eTorn\Models\Turn', 'id_bucket');
    }

}