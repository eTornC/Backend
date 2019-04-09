<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Layout extends Model
{
	protected $table = 'layouts';

	public $timestamps = true;

	protected $fillable = [
		'id', 'name', 'description', 'layout', 'type'
	];

	protected $casts = [
		'layout' => 'array'
	];
}