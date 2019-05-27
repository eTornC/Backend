<?php

namespace eTorn\Controller;

use eTorn\Bbdd\BucketDao;
use eTorn\Bbdd\TurnDao;
use eTorn\Models\Queue;
use eTorn\Models\Store;
use eTorn\Models\Till;
use eTorn\Models\Turn;

use Illuminate\Database\Capsule\Manager as DB;

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
			$actualTurns[0]->ended_at = date('Y-m-d H:i:s');
			$actualTurns[0]->save();
        }

        $queue = $store->queues()->first();

        $bucketDao = new BucketDao();
        $buckets = $bucketDao->getPendingBuckets($queue);

        if ($buckets && $buckets->count() > 0) {
        	$turn = $buckets[0]->turns()
						->where('state', '=', 'WAITING')
						->orderByRaw( "FIELD(type, 'vip', 'hour', 'normal')")
						->orderBy('number')
						->orderBy('created_at')
						->first();

			if ($turn instanceof Turn) {
				$turn->state = 'ATTENDING';
				$turn->atended_at = date('Y-m-d H:i:s');

				return [
					'done' => $turn->save()
				];
			}
		}
        DB::enableQueryLog();

		$turns = $this->turnDao->getNextsNormalTurns($queue);

		//Logger::debug(json_encode(DB::getQueryLog()));

        if (count($turns) > 0) {
			$turns[0]->state = 'ATTENDING';
			$turns[0]->atended_at = date('Y-m-d H:i:s');
			Logger::debug('akitamo2');
			Logger::debug(json_encode($turns[0]));

			return [
				'done' => $turns[0]->save()
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

		if (!$store) {
			return [
				'done' => false,
				'error' => 'Store not found.'
			];
		}

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

    public function newVipTurn($body, $idStore)
	{
		$store = Store::find($idStore);

		if (!$store) {
			return [
				'done' => false,
				'error' => 'Store not found.'
			];
		}

		$queue = $store->queue();

		$bucketDao = new BucketDao();

		$bucket = $bucketDao->getFirstBucketWithTotemTurns($queue);

		if (!$bucket->filled) {

			$vipTurn = new Turn();
			$vipTurn->type = 'vip';
			$vipTurn->number = $this->turnDao->getNextNumberForVipTurn($queue);

			$result = $bucket->turns()->save($vipTurn);

			if ($result === false) {
				return [
					'done' => false
				];
			} else {
				return [
					'done' => true,
					'turn' => $vipTurn
				];
			}
		}


    }

	/**
	 * @return array
	 */
	public function updateHourTurns()
	{
        $stores = Store::all();

        foreach ($stores as $store) {

        	$queue = $store->queue();

        	$now = date('Y-m-d H:i:s', (time()+300));

			$actualBuckets = $queue->buckets()
								->where('hour_start', '<=', $now)
								->where('hour_final', '>=', $now)
								->get();

			if (count($actualBuckets) > 0) {

				foreach ($actualBuckets as $bucket) {

					$turns = $bucket->turns()
								->where('type', '=', 'hour')
								->get();

					if (count($turns) > 0) {

						foreach ($turns as $turn) {

							$lastTurnNum = $this->turnDao->getNextNumberForHourTurn($queue);
							$turn->number = $lastTurnNum;
							$turn->notifyNewTurn();
							$turn->save();
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

