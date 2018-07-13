<?php

class BucketDao extends Dao {

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        return $this->findByProperty(1, 1);
    }

    public function findById($id) {
        return $this->findByProperty('ID', $id);
    }

    public function findByProperty($property, $value) {

        $query = "SELECT * FROM BUCKET WHERE $property = " . (is_string($value) ? "'$value'" : $value);

        $result = parent::query($query);

        $toReturn = array();

        while($row = $result->fetch_assoc()) {

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

    public function save($object) {

        if ($object instanceof Bucket) {

            $query = "INSERT INTO BUCKET (ID_BUCKET_QUEUE, HOUR_START, HOUR_FINAL, QUANTITY) VALUES " .
                        "(" . $object->getIdBucketQueue() . ", '" . $object->getHourStart() . "', '" .
                        $object->getHourFinal() . "', " . $object->getQuantity() . ")";

            return parent::query($query);
        }

        return false;
    }

    public function update($object) {

        if ($object instanceof Bucket) {

            $query = "UPDATE BUCKET SET HOUR_START='" . $object->getHourStart() . "', HOUR_FINAL='" .
                        $object->getHourFinal() . "', QUANTITY=" . $object->getQuantity() . " WHERE ID = "
                        . $object->getId();

            return parent::query($query);
        }

        return false;
    }

    public function delete($id) {

        $query = "DELETE FROM BUCKET WHERE ID = $id";

        return parent::query($query);
    }

    public function getBucketOfThisHour($hour, $bucketQueue) {

        $bucket = $this->searchBucket($hour, $bucketQueue);

        if ($bucket == null) {
            // TODO
        } else {
            return $bucket;
        }
    }

    private function searchBucket($hour, BucketQueue $bucketQueue) {

        $query = "SELECT * FROM BUCKET WHERE HOUR_START < '$hour' AND HOUR_FINAL > '$hour' " .
                    "ID_BUCKET_QUEUE = " . $bucketQueue->getId();

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
}

?>