<?php

namespace eTorn\Controller;

use eTorn\Models\Bucket;
use eTorn\Bbdd\BucketDao;
use eTorn\Models\Store;


class BucketManager {

    private $bucketDao;

    public function __construct() {
        $this->bucketDao = new BucketDao();
    }

    public function findAll() {
        return $this->bucketDao->findAll();
    }

    public function findById($id) {
        return $this->bucketDao->findById($id);
    }

    public function save($body, $idBucketQueue) {

        $bucket = new Bucket();
        $bucket->setQuantity($body->quantity);
        $bucket->setHourStart($body->hourStart);
        $bucket->setHourFinal($body->hourFinal);
        $bucket->setIdBucketQueue($idBucketQueue);

        return array('done' => $this->bucketDao->save($bucket));
    }

    public function update($body, $idBucketQueue, $idBucket) {

        $bucket = new Bucket();
        $bucket->setQuantity($body->quantity);
        $bucket->setHourStart($body->hourStart);
        $bucket->setHourFinal($body->hourFinal);
        $bucket->setIdBucketQueue($idBucketQueue);
        $bucket->setId($idBucket);

        return array('done' => $this->bucketDao->update($bucket));
    }

    public function delete($id) {
        return $this->bucketDao->delete($id);
    }

	public function findNextBuckets($id)
	{
		$store = Store::find($id);
		$queue = $store->queue();

		$this->bucketDao->getBucketOfThisHour(date("Y-m-d H:i:s",time()+3600), $queue); //TODO param temps

		return $queue->buckets()
			->where('hour_start', '>=', date("Y-m-d H:i:s",time()))
			->where('hour_final', '<=', date("Y-m-d H:i:s",time()+60*60))
			->get();
	}

}