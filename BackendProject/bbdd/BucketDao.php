<?php

namespace eTorn\Bbdd;

use eTorn\Controller\Logger;
use eTorn\Models\Bucket;
use eTorn\Models\Queue;
use eTorn\Models\Turn;

class BucketDao
{
    public function findAll()
    {
        return Bucket::all();
    }

    public function findById($id)
    {
        return Bucket::find($id);
    }

    public function findByProperty($property, $value)
    {
        return Bucket::where($property, $value)->get();
    }

    public function getBucketOfThisHour($hour, Queue $bucketQueue): ?Bucket
    {
        $bucket = $this->searchBucket($hour, $bucketQueue);

        if ($bucket == null) {

            $turnDao = new TurnDao();
            $configDao = new ConfigDao();
            $minuteLenghtBucket = $configDao->findByKey('MIN_DURATION_BUCKETS')->value;
            //s$hourOpen = $configDao->findByKey('HOUR_START_ALL_BUCKETS')->value;
			//$hourClose = $configDao->findByKey('HOUR_FINAL_ALL_BUCKETS')->value;

            $lastBucket = $this->getLastBucketInTime($bucketQueue);

			$now = time();

            if ($lastBucket == null || strtotime($lastBucket->hour_final) < ($now - 3600)) {

				$hour_start = (ceil($now / 300) * 300) - 300;

				$lastBucket = new Bucket();
				$lastBucket->hour_start = date('Y-m-d H:i:s', $hour_start);
				$hourFinalTimeStamp = strtotime($lastBucket->hour_start) + ($minuteLenghtBucket * 60) - 1;
				$lastBucket->hour_final = date('Y-m-d H:i:s', $hourFinalTimeStamp);;

				$lastBucket->quantity = $turnDao->calculationBucketSize($lastBucket, $bucketQueue);

				if ($lastBucket->quantity == 0) {
                    $lastBucket->filled = true;
                } else {
                    $lastBucket->filled = false;
                }

				$bucketQueue->buckets()->save($lastBucket);
			}

            while ($lastBucket->hour_final < $hour) {

                $auxBucket = new Bucket();

                $auxBucket->hour_start = date('Y-m-d H:i:s', strtotime($lastBucket->hour_final) + 1);

				$hourFinalTimeStamp = strtotime($auxBucket->hour_start) + ($minuteLenghtBucket * 60) - 1;
				$auxBucket->hour_final = date('Y-m-d H:i:s', $hourFinalTimeStamp);


                $auxBucket->quantity = $turnDao->calculationBucketSize($auxBucket, $bucketQueue);

                if ($auxBucket->quantity == 0) {
                    $auxBucket->filled = true;
                } else {
                    $auxBucket->filled = false;
                }

				$bucketQueue->buckets()->save($auxBucket);

                $lastBucket = $auxBucket;
            }

            return $lastBucket;

        } else {
            return $bucket;
        }
    }

	/**
	 * @param $hour
	 * @param Queue $bucketQueue
	 * @return Bucket
	 */
	private function searchBucket($hour, Queue $bucketQueue): ?Bucket
    {
    	return $bucketQueue->buckets()
			->where('hour_start', '<=', $hour)
			->where('hour_final', '>=', $hour)
			->first();
    }

    private function getLastBucketInTime(Queue $bucketQueue): ?Bucket
	{
		$maxHourStart = $bucketQueue->buckets()->max('hour_start');
		$maxHourFinal = $bucketQueue->buckets()->max('hour_final');

		return $bucketQueue->buckets()
					->where('hour_start', '=', $maxHourStart)
					->where('hour_final', '=', $maxHourFinal)
					->first();
    }

	public function getFirstFreeBucket(Queue $bucketQueue): ?Bucket
	{
		$now = date('Y-m-d H:i:s', time()+300); // avoid actual bucket (+300)
		$bucket = null;

		do {
			$bucket = $this->getBucketOfThisHour($now, $bucketQueue);
			$now = date('Y-m-d H:i:s', strtotime($now)+300);
		} while ($bucket == null || $bucket->filled);

		return $bucket;
	}

	public function getPendingBuckets(Queue $queue)
	{
		$minIdBucket = Turn::join('buckets', 'turns.id_bucket', '=', 'buckets.id')
			->where('buckets.id_queue', '=', $queue->id)
			->where('turns.state', '=', 'WAITING')
			->min('turns.id_bucket');

		$firstbucket = Bucket::find($minIdBucket);
		$actualBucket = $this->getBucketOfThisHour(date('Y-m-d H:i:s'), $queue);

		if ($firstbucket && $actualBucket) {
			return $queue->buckets()
				->where('hour_start', '>=', $firstbucket->hour_start)
				->where('hour_final', '<=', $actualBucket->hour_final)
				->get();
		}

		return null;
	}
}
