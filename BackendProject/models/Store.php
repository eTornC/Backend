<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Store
 * @package eTorn\Models
 * @property int $id
 * @property string $name
 * @property string $photopath
 * @property array $config
 * @property string $created_at
 * @property string $updated_at
 */
class Store extends Model
{
    protected $table = 'stores';

    public $timestamps = true;
    
    protected $fillable = [
        'id', 'name', 'photopath', 'config'
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