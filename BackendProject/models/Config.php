<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed value
 */
class Config extends Model {

    protected $table = 'configs';

	public $timestamps = true;

	protected $fillable = [
		'id', 'key', 'value',
	];

}