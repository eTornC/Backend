<?php

namespace eTorn\Controller;

use eTorn\Bbdd\BucketDao;
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
        	try {
				$actualTurns[0]->state = 'ENDED';
				$actualTurns[0]->ended_at = date('Y-m-d H:i:s');
				$actualTurns[0]->notify('Torn acabat', "El seu torn amb numero " . $actualTurns[0]->number . " ha estat finalitzat.");
				$actualTurns[0]->save();
			} catch (\Exception $e) {
        		Logger::error('TurnManager::nextTurn - ' . $e->getMessage());
			}

			Logger::debug(json_encode($actualTurns[0]));
        }

        $queue = $store->queues()->first();

        $bucketDao = new BucketDao();
        $buckets = $bucketDao->getPendingBuckets($queue);

        if ($buckets && $buckets->count() > 0) {

            $turn = $this->turnDao->getNextTurnOfThisBucket($buckets[0]);
			Logger::debug('akitamo2');
			if ($turn) {
				Logger::debug('akitamo3');
				$turn->state = 'ATTENDING';
				$turn->atended_at = date('Y-m-d H:i:s');

				$turn->notify('Li toca', "El seu torn amb numero " . $turn->number . " comença a atendre's.");

				return [
					'done' => $turn->save()
				];
			}
		}

		$turn = $this->turnDao->getNextNormalTurn($queue);

        if ($turn) {
			Logger::debug('akitamo4');
			$turn->state = 'ATTENDING';
			$turn->atended_at = date('Y-m-d H:i:s');

			$turn->notify('Li toca', "El seu torn amb numero " . $turn->number . " comença a atendre's.");

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

		try {
			if (isset($body->token)) {
				$turn->config = [
					'token' => $body->token
				];
			} else {
				$turn->config = [];
			}
		} catch (\Exception $e) {
			$turn->config = [];
		}

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

    public function newHourTurn($body, $idStore)
	{
		$hour = $body->hour;
		$token = $body->token;

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

        if ($bucket->filled) {
            return [
                'done' => false,
                'err' => 'Bucked Filled'
            ];
        }

        $turn = new Turn();
        $turn->state = 'WAITING';
        $turn->type  = 'hour';
        $turn->config = [
        	'token' => $token
		];

		$bucket->turns()->save($turn);

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
		//Logger::debug($body);

		if (!$store) {
			return [
				'done' => false,
				'error' => 'Store not found.'
			];
		}

		$queue = $store->queue();

		$vipTurn = new Turn();
		$vipTurn->type = 'vip';
		$vipTurn->state = 'WAITING';
		$vipTurn->number = $this->turnDao->getNextNumberForVipTurn($queue);

		try {
			if (isset($body->token)) {
				$vipTurn->config = [
					'token' => $body->token
				];
			} else {
				$vipTurn->config = [];
			}
		} catch (\Exception $e) {
			$vipTurn->config = [];
		}

		$bucketDao = new BucketDao();

		$buckets = $bucketDao->getBucketsFromNow($queue);

		$lastBucket = null;
		$auxTurn = $vipTurn;

		foreach ($buckets as $bucket) {

			if ($bucket->filled) {

				if ($bucket->hasNormalTurns()) {
					$bucket->turns()->save($auxTurn);
					$auxTurn = $bucket->lastNormalTurn();
				}

			} else {
				$result = $bucket->turns()->save($auxTurn);

				if ($bucket->turns()->count() >= $bucket->quantity) {
					$bucket->filled = true;
					$bucket->save();
				}

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

			$lastBucket = $bucket;
		}
		$hour = '';

		if ($lastBucket === null) {
			$hour = date('Y-m-d H:i:s',time() + 300);
		} else {
			$hour = date('Y-m-d H:i:s',strtotime($lastBucket->hour_start) + 300);
		}

		$bucket = $bucketDao->getBucketOfThisHour($hour, $queue);

		$result = $bucket->turns()->save($auxTurn);

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
						    	try {
									$lastTurnNum = $this->turnDao->getNextNumberForHourTurn($queue);
									$turn->number = $lastTurnNum;
									$turn->save();
									$turn->notify(
										'Torn assingat (A' . $turn->number . ')',
										'Ves tirant cap a la parada ' . $store->name . ' en pocs minuts t\'arribarà el torn (A' . $turn->number . ')'
									);
								} catch (\Exception $e) {
						    		Logger::error($e->getMessage());
								}
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

    public function allStoreTurns($idStore)
	{
		$store = Store::find($idStore);

		if (!$store) {
			return [
				'done' => false,
				'err' => 'store not found'
			];
		}

        return $this->turnDao->getListTurns($store);
    }

	public function turnsOfThisToken($token)
	{
		return $this->turnDao->turnsOfThisToken($token);
	}

}

