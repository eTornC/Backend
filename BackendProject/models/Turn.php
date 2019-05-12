<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Turn
 * @package eTorn\Models
 * @property int $id
 * @property int $number
 * @property string $state
 * @property string $type
 */
class Turn extends Model
{
    protected $table = 'turns';

    public $timestamps = true;

    protected $fillable = [
        'id', 'number', 'state', 'type'
    ];

    public function bucket()
    {
        return $this->belongsTo('eTorn\Models\Bucket', 'id_bucket');
    }

    public function till()
    {
        return $this->belongsTo('eTorn\Models\till', 'id_till');
    }
}