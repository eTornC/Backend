<?php

require dirname(__FILE__) . '/../bbdd/BucketQueueDao.php';

class BucketQueueManager {

    private $bucketQueueDao;

    public function __construct() {
        $this->bucketQueueDao = new BucketQueueDao();
    }

    public function findAll() {
        return $this->bucketQueueDao->findAll();
    }

    public function findById($id) {
        return $this->bucketQueueDao->findById($id);
    }

    public function findByStore($idStore) {
        return $this->bucketQueueDao->findByStore($idStore);
    }

    public function save($body, $idStore) {

        $bucketQueue = new BucketQueue();
        $bucketQueue->setIdStore($idStore);
        $bucketQueue->setPriority($body->priority);
        $bucketQueue->setType($body->type);
        $bucketQueue->setIdDestinationQueue($body->idDestinationQueue);

        return array('done' => $this->bucketQueueDao->save($bucketQueue));
    }

    public function delete($id) {
        return array('done' => $this->bucketQueueDao->delete($id));
    }

    public function update($body, $id, $idStore) {

        $bucketQueue = new BucketQueue();
        $bucketQueue->setId($id);
        $bucketQueue->setIdStore($idStore);
        $bucketQueue->setPriority($body->priority);
        $bucketQueue->setType($body->type);
        $bucketQueue->setIdDestinationQueue($body->idDestinationQueue);

        return array('done' => $this->bucketQueueDao->update($bucketQueue));
    }
}

?>

