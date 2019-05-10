<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publicity extends Model
{
	protected $table = 'publicity';

	public $timestamps = true;

	protected $fillable = [
		'id', 'name', 'description', 'html'
	];

	protected $casts = [
		'publicity' => 'array'
	];
}