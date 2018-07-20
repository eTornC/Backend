<?php

require dirname(__FILE__) . '/../bbdd/TurnDao.php';
require dirname(__FILE__) . '/../bbdd/BucketQueueDao.php';
require dirname(__FILE__) . '/../bbdd/BucketDao.php';
require dirname(__FILE__) . '/../bbdd/ConfigDao.php';

class TurnManager {

    private $turnDao;

    public function __construct() {
        $this->turnDao = new TurnDao();
    }

    public function findAll() {
        return $this->turnDao->findAll();
    }

    public function findByIdStoreAndIdQueue($idStore, $idQueue) {
        return $this->turnDao->findByIdQueue($idQueue);
    }

    public function findById($idTurn) {
        return $this->turnDao->findById($idTurn);
    }

    public function save($body, $idStore, $idQueue) {

        $turn = new Turn();

        $turn->setNumber($this->turnDao->getLastTurn($idQueue)->getNumber()+1);
        $turn->setIdBucket('null');
        $turn->setIdQueue($idQueue);
        $turn->setState('WAITING');
        $turn->setIdUser(0); // Body?

        return array('done' => $this->turnDao->save($turn));
    }

    public function update($body, $idStore, $idQueue, $idTurn) {

        $turn = new Turn();
        $turn->setId($idTurn);
        $turn->setState($body->state);
        $turn->setIdQueue($idQueue);

        return array('done' => $this->turnDao->update($turn));
    }

    public function delete($idTurn) {
        return array('done' => $this->turnDao->delete($idTurn));
    }

    public function nextTurn($idStore, $idQueue) {

        $actualTurn = $this->turnDao->getActualTurn($idStore);

        $result = false;

        if ($actualTurn == null) {

            $listOfTurns = $this->turnDao->getListNextsTurns($idStore);

            if (count($listOfTurns) > 0) {
                $listOfTurns[0]->setState('ATTENDING');
                $result = $this->turnDao->update($listOfTurns[0]);
            }
        } else {

            $actualTurn->setState('ENDED');
            $result = $this->turnDao->update($actualTurn);

            if ($result) {

                $listOfTurns = $this->turnDao->getListNextsTurns($idStore);

                if (count($listOfTurns) > 0) {
                    $listOfTurns[0]->setState('ATTENDING');
                    $result = $this->turnDao->update($listOfTurns[0]);
                }
            }
        }

        return array('done' => (bool) $result);
    }

    public function getActualTurn($idStore, $idQueue) {
        $turns = $this->turnDao->getActualTurn($idStore);

        if (count($turns) == 0) {
            return array('error' => 'no turn');
        }

        return $turns;
    }

    public function newNormalTurn($body, $idStore) {

        $queueDao = new QueueDao();

        $normalQueue = $queueDao->getNormalQueue($idStore);

        $queueDao->close();

        if ($normalQueue == null) {
            return array('error' => 'No normal queue for this store');
        }

        $lastTurn = $this->turnDao->getLastTurn($normalQueue->getId());

        $newTurn = $this->createTurn($lastTurn->getNumber(), $normalQueue->getId());

        if ($this->turnDao->save($newTurn)) {
            return array('number' => $newTurn->getNumber());
        }

        return array('done' => false);
    }

    public function newHourTurn($hour, $idStore) {

        if ($hour < strtotime('now')) {
            return array('done' => false);
        }

        $hour = date('Y-m-d H:i:s', $hour);

        $bucketQueueDao = new BucketQueueDao();
        $bucketQueue = $bucketQueueDao->findByStore($idStore);
        $bucketQueueDao->close();

        $bucketDao = new BucketDao();
        $bucket = $bucketDao->getBucketOfThisHour($hour, $bucketQueue);
        $bucketDao->close();

        if ($bucket == null) {
            return array('done' => false);
        }

        $turn = new Turn();
        $turn->setIdBucket($bucket->getId());
        $turn->setState('WAITING');
        $turn->setIdUser(0);

        return array('done' => ($this->turnDao->saveHourTurn($turn)));
    }

    public function newVipTurn($body, $idStore) {

        $queueDao = new QueueDao();

        $vipQueue = $queueDao->getVipQueue($idStore);

        $queueDao->close();

        if ($vipQueue == null) {
            return array('error' => 'No VIP queue for this store');
        }

        $lastTurn = $this->turnDao->getLastTurn($vipQueue->getId());

        $newTurn = $this->createTurn($lastTurn->getNumber(), $vipQueue->getId());

        if ($this->turnDao->save($newTurn)) {
            return array('number' => $newTurn->getNumber());
        }

        return array('done' => false);

    }

    private function createTurn($lastNumber, $queueId) {

        $newTurn = new Turn();
        $newTurn->setIdQueue($queueId);
        $newTurn->setNumber($lastNumber+1);
        $newTurn->setIdUser(0);
        $newTurn->setState('WAITING');
        $newTurn->setIdBucket('NULL');

        return $newTurn;
    }


}

?>