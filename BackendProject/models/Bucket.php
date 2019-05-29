<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
     * @return BelongsTo
     */
    public function queue()
    {
        return $this->belongsTo('eTorn\Models\Queue', 'id_queue');
    }

    public function queueInstance(): Queue
    {
        return $this->queue()->first();
    }

    /**
     * @return HasMany
     */
    public function turns()
    {
        return $this->hasMany('eTorn\Models\Turn', 'id_bucket');
    }

    public function hasNormalTurns(): bool
	{
		$turns = $this->turns()->get();

		foreach ($turns as $turn) {
			if ($turn->type === 'normal') {
				return true;
			}
		}

		return false;
	}

	public function lastNormalTurn(): ?Turn
	{
		return $this->turns()
				->where('type', '=', 'normal')
				->orderBy('created_at', 'desc')
				->first();
	}

}