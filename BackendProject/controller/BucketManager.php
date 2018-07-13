<?php

require dirname(__FILE__) . '/../bbdd/BucketDao.php';

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

}