<?php

class BucketQueueDao extends Dao {

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        return $this->findByProperty(1, 1);
    }

    public function findById($id) {
        return $this->findByProperty('ID', $id)[0];
    }

    public function findByProperty($property, $value) {

        $query = "SELECT * FROM BUCKET_QUEUE WHERE $property = " . (is_string($value) ? "'$value'" : $value);

        $result = parent::query($query);

        $toReturn = array();

        while($row = $result->fetch_assoc()) {

            $bucketQueue = new BucketQueue();
            $bucketQueue->setId($row['ID']);
            $bucketQueue->setIdStore($row['ID_STORE']);
            $bucketQueue->setIdDestinationQueue($row['ID_DESTINATION_QUEUE']);
            $bucketQueue->setType($row['QUEUE_TYPE']);
            $bucketQueue->setPriority($row['PRIORITY']);

            $toReturn[] = $bucketQueue;
        }

        return $toReturn;
    }

    public function save($object) {

        if ($object instanceof BucketQueue) {

            $query = "INSERT INTO BUCKET_QUEUE (ID_STORE, ID_DESTIONATION_QUEUE, QUEUE_TYPE, PRIORITY) VALUES "
                        . "(" . $object->getIdStore() . ", " . $object->getIdDestinationQueue() . ", '" .
                        $object->getType() . "', " . $object->getPriority();

            return parent::query($query);
        }

        return false;
    }

    public function update($object) {

        if ($object instanceof BucketQueue) {

            $query = "UPDATE BUCKET_QUEUE SET ID_DESTIONATION_QUEUE=" . $object->getIdDestinationQueue() . ", QUEUE_TYPE='" .
                        $object->getType() . "', PRIORITY=" . $object->getPriority() . " WHERE ID=" . $object->getId();

            return parent::query($query);
        }

        return false;
    }

    public function delete($id) {

        $query = "DELETE FROM BUCKET_QUEUE WHERE ID = " . $id;
        return parent::query($query);
    }

    public function findByStore($idStore) {
        return $this->findByProperty('ID_STORE', $idStore)[0];
    }
}