<?php

class QueueDao extends Dao {

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        return $this->findByProperty(1, 1);
    }

    public function findById($id) {
        return $this->findByProperty("ID", $id);
    }

    public function findByProperty($property, $value) {

        $query = "SELECT * FROM QUEUE WHERE $property = " . (is_string($value) ? "'$value'" : $value);

        $result = parent::query($query);

        $toReturn = array();

        while($row = $result->fetch_assoc()) {

            $queue = new Queue();
            $queue->setId($row['ID']);
            $queue->setType($row['QUEUE_TYPE']);
            $queue->setPriority($row['PRIORITY']);
            $queue->setIdStore($row['ID_STORE']);

            $toReturn[] = $queue;
        }

        return $toReturn;
    }

    public function save($queue) {

        if ($queue instanceof Queue) {

            $query = "INSERT INTO QUEUE (QUEUE_TYPE, PRIORITY, ID_STORE) VALUES ('" . $queue->getType() . "', " .
                        $queue->getPriority() . ", " . $queue->getIdStore() . ")";

            return parent::query($query);
        }

        return false;
    }

    public function update($queue) {

        if ($queue instanceof Queue) {

            $query = "UPDATE QUEUE SET QUEUE_TYPE='" . $queue->getType() . "', PRIORITY=" . $queue->getPriority() .
                        " WHERE ID=" . $queue->getId();

            return parent::query($query);
        }

        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM QUEUE WHERE ID = $id";
        return parent::query($query);
    }

    public function findByIdStore($idStore) {
        return $this->findByProperty("ID_STORE",  $idStore);
    }
}