<?php

namespace eTorn\Controller;

use eTorn\Bbdd\BucketDao;
use eTorn\Bbdd\QueueDao;
use eTorn\Bbdd\TurnDao;
use eTorn\Models\Queue;
use eTorn\Models\Store;
use eTorn\Models\Till;
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

    public function nextTurn($idStore, $idTill)
	{
		$store = Store::find($idStore);

		if ($idTill) {
			$till = Till::find($idTill);
		}

		if (!$store) {
			return [
				'error' => 'Store not found'
			];
		}

        $actualTurns = $this->turnDao->getActualTurns($store);

        if (count($actualTurns) > 0) {
        	$actualTurns[0]->state = 'ENDED';
			$actualTurns[0]->save();
        }

        $bucketDao = new BucketDao();
        $buckets = $bucketDao->getPendingBuckets($store->queues()->first());

        if ($buckets->count() > 0) {
        	$turn = $buckets[0]->turns()
						->where('state', '=', 'WAITING')
						->orderByRaw( "FIELD(type, 'vip', 'hour', 'normal')")
						->orderBy('number')
						->orderBy('created_at')
						->first();

        	$turn->state = 'ATTENDING';

			return [
				'done' => $turn->save()
			];
		}

        return ['done' => false];
    }

    public function getActualTurns($idStore)
	{
    	$store = Store::find($idStore);

    	if (!$store) {
    		return [
    			'error' => 'Store not found'
			];
		}

        $turns = $this->turnDao->getActualTurns($store);

        if (count($turns) === 0) {
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

        $bucketDao = new BucketDao();
		$bucket = $bucketDao->getFirstFreeBucket($bucketQueue);

		$turnDao = new TurnDao();

		$turn = new Turn();
		$turn->state = 'WAITING';
		$turn->type  = 'normal';
		$turn->number = $turnDao->getNextNumberForWaitingTurn($bucketQueue);

		$turn = $bucket->turns()->save($turn);

		if ($bucket->turns()->count() >= $bucket->quantity) {
			$bucket->filled = true;
			$bucket->save();
		}

		if ($turn) {
			return [
				'done' => true,
				'turn' => $turn
			];
		} else {
			return [
				'done' => false
			];
		}
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

        $bucketDao = new BucketDao();

		$bucket = $bucketDao->getBucketOfThisHour($hour, $bucketQueue);

        if ($bucket == null) {
            return array('done' => false);
        }

        $turn = new Turn();
        $turn->state = 'WAITING';
        $turn->type  = 'hour';

		$turn = $bucket->turns()->save($turn);

		if ($bucket->turns()->count() >= $bucket->quantity) {
			$bucket->filled = true;
			$bucket->save();
		}

		if ($turn) {
			return [
				'done' => true,
				'turn' => $turn
			];
		} else {
			return [
				'done' => false
			];

		}
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

    public function waitingTurns($idStore)
	{
		$store = Store::find($idStore);
		// TODO FAILS
        return $this->turnDao->getListNextsTurns($store);
    }

}

?>