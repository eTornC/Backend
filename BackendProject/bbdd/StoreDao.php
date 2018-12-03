<?php

require(dirname(__FILE__) . '/Dao.php');

class StoreDao extends Dao {

    function __construct() {
        parent::__construct();
    }

    public function findAll() {

        $query = "SELECT * FROM STORE";

        $result = parent::query($query);

        $toReturn = array();

        while($row = $result->fetch_assoc()) {

            $store = new Store();
            $store->setId($row['ID']);
            $store->setName($row['NAME']);
            $store->setDate($row['DATE_CREATED']);
            $store->setPhotopath($row['PHOTO_PATH']);

            $toReturn[] = $store;
        }

        return $toReturn;
    }

    public function findById($id) {
        return $this->findByProperty("ID", $id);
    }

    public function findByProperty($property, $value) {

        $query = "SELECT * FROM STORE WHERE $property = $value";

        $result = parent::query($query);

        $toReturn = array();

        while($row = $result->fetch_assoc()) {

            $store = new Store();
            $store->setId($row['ID']);
            $store->setName($row['NAME']);
            $store->setDate($row['DATE_CREATED']);
            $store->setPhotopath($row['PHOTO_PATH']);

            $toReturn[] = $store;
        }

        return $toReturn;
    }

    public function save($queue) {
        if ($queue instanceof Store) {
            $query = "INSERT INTO STORE (NAME, DATE_CREATED, PHOTO_PATH) VALUES ('" . $queue->getName() . "', NOW(), '" . $queue->getPhotopath() ."')";
            return parent::query($query);
        } else {
            return false;
        }
    }

    public function update($object) {
        if ($object instanceof Store) {
            $query = "UPDATE STORE SET NAME='" . $object->getName() . "', PHOTO_PATH='" . $object->getPhotopath() . "' WHERE ID = " . $object->getId();
            return parent::query($query);
        } else {
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM STORE WHERE ID = $id";
        return parent::query($query);
    }
}