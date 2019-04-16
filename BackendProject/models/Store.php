<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';

    public $timestamps = true;
    
    protected $fillable = [
        'id', 'name', 'date', 'photopath', 'config'
    ];

    protected $casts = [
        'config' => 'array'
    ];

    public function queues()
    {
        return $this->hasMany('eTorn\Models\Queue', 'id_store');
    }

    public function tills()
    {
        return $this->hasMany('eTorn\Models\Till', 'id_store');
    }

    public function turns()
    {
        $allTurns = [];

        $queues = $this->queues()->get();

        foreach ($queues as $queue) {
            $turns = $queue->turns()->get();
            array_push($allTurns, $turns);
        }

        return $allTurns;
    }

}