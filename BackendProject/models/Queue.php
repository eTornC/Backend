<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model 
{
    protected $table = 'queues';

    public $timestamps = true;

    protected $fillable = [
        'id', 'type'
    ];

    /**
     * @return array
     */
    public function turns()
    {
        $buckets = $this->buckets()->get();

        $turns = [];

        foreach ($buckets as $bucket) {
            array_push($turns, $bucket->turns());
        }

        return $turns;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buckets()
    {
        return $this->hasMany('eTorn\Models\Bucket', 'id_queue');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function firstBucketNotFilled()
    {
        return $this->buckets()
                    ->where('filled', '=', false)
                    ->get();
    }

    /**
     * @param $hour
     * @return Bucket
     */
    public function getBucketOfThisHour($hour)
    {
        $bucket = $this->buckets()
                    ->where('hour_start', '<', $hour)
                    ->where('hour_final', '>', $hour)
                    ->first();

        if (!$bucket) {



        }

        return $bucket;
    }
}