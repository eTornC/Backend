<?php

namespace eTorn\Controller;

use eTorn\Bbdd\QueueDao;
use eTorn\Bbdd\TurnDao;
use eTorn\Models\Queue;
use eTorn\Models\Store;
use eTorn\Models\Turn;

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

    public function nextTurn($idStore) {

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

        return ['done' => (bool) $result];
    }

    public function getActualTurn($idStore) {
        $turns = $this->turnDao->getActualTurn($idStore);

        if (count($turns) == 0) {
            return ['error' => 'no turn'];
        }

        return $turns;
    }

    public function newNormalTurn($body, $idStore)
    {
        $store = Store::find($idStore);

        $bucketQueue = $store->queues()->first();

        if ($bucketQueue == null) {
            return [
                'done' => false,
                'error' => 'No queue for this store'
            ];
        }

        return $bucketQueue->firstBucketNotFilled();



        $lastTurn = $this->turnDao->getLastTurn($normalQueue->getId());

        $newTurn = $this->createTurn($lastTurn->getNumber(), $normalQueue->getId());

        if ($this->turnDao->save($newTurn)) {
            return ['number' => $newTurn->getNumber()];
        }

        return ['done' => false];
    }

    public function newHourTurn($hour, $idStore) {

        if ($hour < strtotime('now')) {
            return [
                'done' => false,
                'err' => 'hour from the past'
            ];
        }

        $hour = date('Y-m-d H:i:s', $hour);

        $store = Store::find($idStore);

        $bucketQueue = $store->queues()->first();

        $bucket = $bucketQueue->getBucketOfThisHour($hour);

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

        if (!$vipQueue) {
            return array('error' => 'No VIP queue for this store');
        }

        $lastTurn = $vipQueue->getLastTurn();

        $newTurn = new Turn([
            'number' => $lastTurn->number+1,
            'state' => 'WAITING'
        ]);

        if ($vipQueue->turns()->save($newTurn)){
            return [
                'done' => true,
                'number' => $newTurn->number
            ];
        }

        return array('done' => false);
    }

    public function updateHourTurns() {

        $bucketDao = new BucketDao();
        $bucketQueueDao = new BucketQueueDao();
        $queueDao = new QueueDao();

        $actualBuckets = $bucketDao->getActualBuckets();

        foreach ($actualBuckets as $bucket) { // TODO notifications

            $bucketQueue = $bucketQueueDao->findById($bucket->getIdBucketQueue());

            $turnsToUpdate = $this->turnDao->getMobileTurnsToUpdate($bucket);

            if (count($turnsToUpdate) > 0) {

                $queue = $queueDao->findById($bucketQueue->getIdDestinationQueue());

                $lastTurnNum = $this->turnDao->getLastTurn($queue->getId())->getNumber();

                $turnNumber = $lastTurnNum + 1;

                foreach ($turnsToUpdate as $turn) {
                    if ($turn instanceof Turn) {
                        $turn->setNumber($turnNumber);
                        $turn->setIdQueue($queue->getId());
                        $this->turnDao->update($turn);
                        $turnNumber++;
                    }
                }
            }
        }

        $bucketDao->close();
        $bucketQueueDao->close();
        $queueDao->close();

        return array('done' => true);
    }

    public function waitingTurns($idStore) {
        return $this->turnDao->getListNextsTurns($idStore);
    }

}

?>