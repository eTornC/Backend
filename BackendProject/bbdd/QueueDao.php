<?php

/**
 * Created by PhpStorm.
 * User: Josep
 * Date: 12/06/2018
 * Time: 15:58
 */

//require dirname(__FILE__) . '/Dao.php';

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

    public function save($object) {

        if ($object instanceof Queue) {

            $query = "INSERT INTO QUEUE (QUEUE_TYPE, PRIORITY, ID_STORE) VALUES ";

        }

        return false;
    }

    public function update($object) {
        // TODO: Implement update() method.
    }

    public function delete($id) {
        $query = "DELETE FROM QUEUE WHERE ID = $id";
        return parent::query($query);
    }
}