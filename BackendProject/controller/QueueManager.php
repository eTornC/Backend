<?php

namespace eTorn\Controller;

use eTorn\Bbdd\QueueDao;
use eTorn\Models\Queue;

class QueueManager {

    private $queueDao;

    public function __construct() {
        $this->queueDao = new QueueDao();
    }

    public function findAll($idStore) {
        try {
            $idStore = (int) htmlentities(addslashes($idStore));
            return $this->queueDao->findByIdStore($idStore);
        } catch (\Exception $e) {
            return array();
        }
    }

    public function findById($idStore, $idQueue) {
        try {
            $idQueue = (int) htmlentities(addslashes($idQueue));
            $result = $this->queueDao->findById($idQueue);

            if ((count($result) == 1) && ($result[0]->getIdStore() == $idStore)) {
                    return $result;

            }
        } catch (\Exception $e) {
            return array();
        }

        return array();
    }

    public function save($body, $idStore) {

        $queue = new Queue();
        $queue->setIdStore((int) htmlentities(addslashes($idStore)));
        $queue->setPriority($body->priority);
        $queue->setType($body->type);

        return array('done' => $this->queueDao->save($queue));
    }

    public function update($body, $idStore, $idQueue) {

        $queue = new Queue();
        $queue->setIdStore((int) htmlentities(addslashes($idStore)));
        $queue->setId((int) htmlentities(addslashes($idQueue)));
        $queue->setType($body->type);
        $queue->setPriority($body->priority);

        return array('done' => $this->queueDao->update($queue));
    }

    public function delete($idStore, $idQueue) {

        return array('done' => $this->queueDao->delete((int) htmlentities(addslashes($idQueue))));
    }
}