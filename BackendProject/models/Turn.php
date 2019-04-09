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
}