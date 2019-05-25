<?php

namespace eTorn\Controller;

use eTorn\Bbdd\BucketDao;
use eTorn\Bbdd\QueueDao;
use eTorn\Bbdd\TurnDao;
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
			    'done'  => false,
				'error' => 'Store not found'
			];
		}

        $actualTurns = $this->turnDao->getActualTurns($store);

        if (count($actualTurns) > 0) {
        	$actualTurns[0]->state = 'ENDED';
			$actualTurns[0]->ended_at = date('Y-m-d H:i:s');
			$actualTurns[0]->save();
        }

        $queue = $store->queues()->first();

        $bucketDao = new BucketDao();
        $buckets = $bucketDao->getPendingBuckets($queue);

        if ($buckets && $buckets->count() > 0) {

            $turn = $this->turnDao->getNextTurnOfThisBucket($buckets[0]);

			if ($turn) {
				$turn->state = 'ATTENDING';
				$turn->atended_at = date('Y-m-d H:i:s');

				return [
					'done' => $turn->save()
				];
			}
		}

		$turn = $this->turnDao->getNextNormalTurn($queue);

        if ($turn) {
			$turn->state = 'ATTENDING';
			$turn->atended_at = date('Y-m-d H:i:s');

			return [
				'done' => $turn->save()
			];
		}

        return [
        	'done' => false,
			'msg' => 'No turns'
		];
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

        if (!$store) {
			return [
				'done' => false,
				'error' => 'Store not found.'
			];
		}

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

	/**
	 * @return array
	 */
	public function updateHourTurns()
	{
        $stores = Store::all();

        foreach ($stores as $store) {

        	$queue = $store->queue();

        	$nextBucketHour = date('Y-m-d H:i:s', (time()+300));

			$nextHourBuckets = $queue->buckets()
								->where('hour_start', '<=', $nextBucketHour)
								->where('hour_final', '>=', $nextBucketHour)
								->get();

			if (count($nextHourBuckets) > 0) {

				foreach ($nextHourBuckets as $bucket) {

					$turns = $bucket->turns()
								->where('type', '=', 'hour')
                                ->where('state', '=', 'WAITING')
								->get();

					if (count($turns) > 0) {

						foreach ($turns as $turn) {

						    if (!$turn->hasNumber())  {
                                $lastTurnNum = $this->turnDao->getNextNumberForHourTurn($queue);
                                $turn->number = $lastTurnNum;
                                $turn->notifyNewTurn();
                                $turn->save();
                            }
						}
					}
				}
			}
		}

        return array('done' => true);
    }

    public function waitingTurns($idStore)
	{
		$store = Store::find($idStore);

		if (!$store) {
			return [
				'done' => false,
				'err' => 'store not found'
			];
		}

        return $this->turnDao->getListNextsTurns($store);
    }

}

