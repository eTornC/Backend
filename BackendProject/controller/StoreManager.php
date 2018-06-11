<?php

/**
 * Created by PhpStorm.
 * User: yous
 * Date: 11/06/18
 * Time: 19:31
 */
require dirname(__FILE__) . '/../bbdd/StoreDao.php';

class StoreManager {

    private $storeDao;

    function __construct() {
        $this->storeDao = new StoreDao();
    }

    public function findAll() {
        return $this->storeDao->findAll();
    }

    public function findById($id) {

        if (!is_integer($id)) {
            return [];
        }

        $id = htmlentities(addslashes($id));

        return $this->storeDao->findById($id);
    }

    public function delete($id) {

        if (!is_integer($id)) {
            return [];
        }

        $id = htmlentities(addslashes($id));

        return $this->storeDao->delete($id);
    }
}