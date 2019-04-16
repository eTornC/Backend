<?php

namespace eTorn\Bbdd;

use eTorn\Models\Bucket;

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

    public function getBucketOfThisHour($hour, Queue $bucketQueue)
    {
        $bucket = $this->searchBucket($hour, $bucketQueue);

        if ($bucket == null) {

            $configDao = new ConfigDao();
            $minuteLenghtBucket = $configDao->findByKey('MIN_DURATION_BUCKETS')->getValue();
            $hourOpen = $configDao->findByKey('HOUR_START_ALL_BUCKETS')->getValue();
            $hourClose = $configDao->findByKey('HOUR_FINAL_ALL_BUCKETS')->getValue();

            $lastBucket = $this->getLastBucketInTime($bucketQueue);

            // TODO from now

            if ($lastBucket == null) {
                return null;
             }

            while ($lastBucket->getHourStart() < $hour) {

                $auxBucket = new Bucket();
                $auxBucket->setIdBucketQueue($bucketQueue->getId());
                $auxBucket->setQuantity(3); // TODO with parameter
                $auxBucket->setHourStart(date('Y-m-d H:i:s', strtotime($lastBucket->getHourFinal()) + 1));

                $hourFinalTimeStamp = strtotime($auxBucket->getHourStart()) + ($minuteLenghtBucket * 60) - 1;

                $auxBucket->setHourFinal(date('Y-m-d H:i:s', $hourFinalTimeStamp));

                $this->save($auxBucket);

                $lastBucket = $auxBucket;
            }

            $lastBucket = $this->completeThisBucket($lastBucket);

            return $lastBucket;

        } else {
            return $bucket;
        }
    }

    private function searchBucket($hour, Queue $bucketQueue)
    {

        $query = "SELECT * FROM BUCKET WHERE HOUR_START < '$hour' AND HOUR_FINAL > '$hour' " .
                    "AND ID_BUCKET_QUEUE = " . $bucketQueue->getId();

        $result = parent::query($query);

        if ($row = $result->fetch_assoc()) {

            $bucket = new Bucket();
            $bucket->setId($row['ID']);
            $bucket->setIdBucketQueue($row['ID_BUCKET_QUEUE']);
            $bucket->setHourStart($row['HOUR_START']);
            $bucket->setHourFinal($row['HOUR_FINAL']);
            $bucket->setDateCreated($row['DATE_CREATED']);
            $bucket->setQuantity($row['QUANTITY']);

            return $bucket;
        }

        return null;
    }

    private function getLastBucketInTime(BucketQueue $bucketQueue) {

        $bucketQueueId = $bucketQueue->getId();

        $query = "SELECT *
                  FROM BUCKET
                  WHERE HOUR_START = (SELECT MAX(HOUR_START)
                                      FROM BUCKET
                                      WHERE ID_BUCKET_QUEUE = $bucketQueueId)
                  AND HOUR_FINAL = (SELECT MAX(HOUR_FINAL)
                                      FROM BUCKET
                                      WHERE ID_BUCKET_QUEUE = $bucketQueueId)
                                      AND BUCKET.ID_BUCKET_QUEUE = $bucketQueueId";

        $result = parent::query($query);

        if ($row = $result->fetch_assoc()) {

            $bucket = new Bucket();
            $bucket->setId($row['ID']);
            $bucket->setIdBucketQueue($row['ID_BUCKET_QUEUE']);
            $bucket->setHourStart($row['HOUR_START']);
            $bucket->setHourFinal($row['HOUR_FINAL']);
            $bucket->setDateCreated($row['DATE_CREATED']);
            $bucket->setQuantity($row['QUANTITY']);

            return $bucket;
        }

        return null;
    }

    private function completeThisBucket(Bucket $bucket) {

        $query = "SELECT * FROM BUCKET WHERE ID_BUCKET_QUEUE = " . $bucket->getIdBucketQueue() .
                    " AND HOUR_START = '" . $bucket->getHourStart() . "' AND HOUR_FINAL = '"
                        . $bucket->getHourFinal() . "'";

        $result = parent::query($query);

        if ($row = $result->fetch_assoc()) {

            $bucket = new Bucket();
            $bucket->setId($row['ID']);
            $bucket->setIdBucketQueue($row['ID_BUCKET_QUEUE']);
            $bucket->setHourStart($row['HOUR_START']);
            $bucket->setHourFinal($row['HOUR_FINAL']);
            $bucket->setDateCreated($row['DATE_CREATED']);
            $bucket->setQuantity($row['QUANTITY']);

            return $bucket;
        }

        return null;
    }

    public function getActualBuckets() {

        $query = "SELECT * FROM BUCKET WHERE HOUR_START < NOW() AND HOUR_FINAL > NOW()";

        $result = parent::query($query);

        $toReturn = array();

        while ($row = $result->fetch_assoc()) {

            $bucket = new Bucket();
            $bucket->setId($row['ID']);
            $bucket->setIdBucketQueue($row['ID_BUCKET_QUEUE']);
            $bucket->setHourStart($row['HOUR_START']);
            $bucket->setHourFinal($row['HOUR_FINAL']);
            $bucket->setDateCreated($row['DATE_CREATED']);
            $bucket->setQuantity($row['QUANTITY']);

            $toReturn[] = $bucket;
        }

        return $toReturn;
    }
}

?>