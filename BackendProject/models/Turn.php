<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;

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