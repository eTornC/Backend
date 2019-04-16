<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;

class Till extends Model
{
    protected $table = 'tills';

    public $timestamps = true;

    protected $fillable = [
        'id', 'name', 'active'
    ];

    public function store()
    {
        return $this->belongsTo('eTorn\Models\Store', 'id_store');
    }

    public function turns()
    {
        return $this->hasMany('eTorn\Models\Turn', 'id_till');
    }
}