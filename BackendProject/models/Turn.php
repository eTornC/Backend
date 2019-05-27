<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Turn
 * @package eTorn\Models
 * @property int $id
 * @property int $number
 * @property string $state
 * @property string $type
 * @property string $atended_at
 * @property string $ended_at
 */
class Turn extends Model
{
    protected $table = 'turns';

    public $timestamps = true;

    protected $fillable = [
        'id', 'number', 'state', 'type',
		'atended_at', 'ended_at'
    ];

	/**
	 * @return BelongsTo
	 */
	public function bucket(): BelongsTo
    {
        return $this->belongsTo('eTorn\Models\Bucket', 'id_bucket');
    }

	/**
	 * @return BelongsTo
	 */
	public function till(): BelongsTo
    {
        return $this->belongsTo('eTorn\Models\till', 'id_till');
    }

    public function hasNumber(): bool
    {
        return ($this->number != null);
    }

    public function notifyNewTurn(): void
	{

	}
}