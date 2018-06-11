<?php

/**
 * Created by PhpStorm.
 * User: yous
 * Date: 11/06/18
 * Time: 19:27
 */

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

            $toReturn[] = $store;

        }

        return $toReturn;
    }

    public function findById()
    {
        // TODO: Implement findById() method.
    }

    public function findByProperty($property, $value)
    {
        // TODO: Implement findByProperty() method.
    }

    public function save($object)
    {
        // TODO: Implement save() method.
    }

    public function update($object)
    {
        // TODO: Implement update() method.
    }

    public function delete($object)
    {
        // TODO: Implement delete() method.
    }
}