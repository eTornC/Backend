<?php

namespace eTorn\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


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
    	$turns = [];

        $this->buckets()->each(function (Bucket $b) use ($turns) {

        	$b->turns();
		});

        return $turns;
    }

    /**
     * @return HasMany
     */
    public function buckets()
    {
        return $this->hasMany('eTorn\Models\Bucket', 'id_queue');
    }

    /**
     * @return Model|null
     */
    public function firstBucketNotFilled()
    {
        return $this->buckets()
                    ->where('filled', '=', false)
                    ->first();
    }

}