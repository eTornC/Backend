<?php

/**
 * Created by PhpStorm.
 * User: Josep
 * Date: 12/06/2018
 * Time: 11:48
 */
require dirname(__FILE__) . '/../bbdd/QueueDao.php';

class QueueManager {

    private $queueDao;

    public function __construct() {
        $this->queueDao = new QueueDao();
    }

    public function findAll($idStore) {
        try {
            $idStore = (int) htmlentities(addslashes($idStore));
            return $this->queueDao->findByProperty("ID_STORE",  $idStore);
        } catch (Exception $e) {
            return array();
        }
    }

    public function findById($idStore, $idQueue) {
        try {
            $idQueue = (int) htmlentities(addslashes($idQueue));
            $result = $this->queueDao->findByProperty("ID",  $idQueue);

            if (count($result) == 1) {
                if ($result[0]->getIdStore() == $idStore) {
                    return $result;
                }
            }
        } catch (Exception $e) {
            return array();
        }

        return array();
    }

    public function save($json_decode, $id) {
        return false;
    }
}