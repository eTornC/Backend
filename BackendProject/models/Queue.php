<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model 
{
    protected $table = 'queues';

    public $timestamps = true;

    protected $fillable = [
        'id', 'type', 'priority'
    ];

    public function turns()
    {
        return $this->hasMany('eTorn\Models\Turn', 'id_queue');
    }

    public function getLastTurn()
    {
        $turn = $this->turns()
                    ->where('state', '=', 'WAITING')
                    ->orderBy('state', 'desc')
                    ->first();

        if (!$turn) {
            return new Turn([
                'number' => 0
            ]);
        }

        return $turn;
    }
}