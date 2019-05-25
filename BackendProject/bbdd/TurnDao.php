<?php

namespace eTorn\Bbdd;

use eTorn\Controller\Logger;
use eTorn\Models\Bucket;
use eTorn\Models\Queue;
use eTorn\Models\Store;
use eTorn\Models\Turn;
use Illuminate\Database\Eloquent\Model;

class TurnDao
{
    public function findAll()
    {
        return Turn::all();
    }

    public function findById($id)
    {
        return Turn::where("id", $id)->first();
    }

    public function findByProperty($property, $value)
    {
        return Turn::where($property, $value)->get();
    }

    public function save(Turn $turn)
    {
        return $turn->save();
    }

    public function delete($id)
    {
        return $this->findById($id)->delete();
    }

    public function findByIdQueue($idQueue)
    {
        return $this->findByProperty('ID_QUEUE', $idQueue);
    }

	/**
	 * @param Store $store
	 * @return mixed
	 */
	public function getActualTurns(Store $store)
    {
    	$queue = $store->queues()->first();

    	return Turn::join('buckets', 'turns.id_bucket', '=', 'buckets.id')
			->where('buckets.id_queue', '=', $queue->id)
			->where('state', 'ATTENDING')
			->select('turns.*')
			->get();
    }

	public function getNextNumberForWaitingTurn(Queue $queue): ?int
	{
		$turn = $queue->buckets()
			->join('turns', 'turns.id_bucket', '=', 'buckets.id')
			->where('turns.type', '=', 'normal')
			->max('turns.number');

		if ($turn) {
			return $turn + 1;
		}

		return 1;
	}

	public function getNextNumberForHourTurn(Queue $queue): ?int
	{
		$turn = $queue->buckets()
			->join('turns', 'turns.id_bucket', '=', 'buckets.id')
			->where('turns.type', '=', 'hour')
			->max('turns.number');

		if ($turn) {
			return $turn + 1;
		}

		return 1;
	}

    public function getListNextsTurns(Store $store)
	{
		$queue = $store->queues()->first();

		return Turn::where('turns.state' , '=', 'WAITING')
			->join('buckets', 'turns.id_bucket', '=', 'buckets.id')
			->where('buckets.id_queue', '=', $queue->id)
			->orderBy('turns.created_at', 'asc')
			->get();
    }

	public function getNextsNormalTurns(Queue $queue): array
	{
		$buckets = $queue->buckets()->get();

		$turns = [];

		$buckets->each(function (Bucket $bucket) use (&$turns) {

			$turnsBucket = $bucket->turns()
				->where('state', '=', 'WAITING')
				->where('type', '=', 'normal')
				->orderBy('number')
				->get();

			$turnsBucket->each(function (Turn $turn) use (&$turns) {
				array_push($turns, $turn);
			});
		});

		return $turns;
	}

	public function getNextNormalTurn(Queue $queue): ?Turn
    {
	    $turns = $this->getNextsNormalTurns($queue);

	    if (count($turns) > 0) {
	        return $turns[0];
        }

	    return null;
    }

    public function getNextTurnOfThisBucket(Bucket $bucket): ?Turn
    {
        return $bucket->turns()
                ->where('state', '=', 'WAITING')
                ->orderByRaw( "FIELD(type, 'vip', 'hour', 'normal')")
                ->orderBy('number')
                ->orderBy('created_at')
                ->first();
    }

    public function calculationBucketSize(Bucket $bucket, Queue $queue): int
    {
        $timeHourStart = strtotime($bucket->hour_start);

        Logger::debug(date('Y-m-d H:i:s', $timeHourStart-3600));

        $avg = $queue->buckets()
                    ->join('turns', 'buckets.id', '=', 'turns.id_bucket')
                    ->where('turns.ended_at', '>', date('Y-m-d H:i:s', time()-3600)) // TODO param!!
                    ->where('turns.state', '=', 'ENDED')
                    ->selectRaw(" AVG(TIMESTAMPDIFF(SECOND, atended_at, ended_at)) as time")
                    ->first();

        Logger::debug(json_encode($avg));

        if ($avg['time']) {
            Logger::debug(floor(300 / $avg['time']));
            return floor(300 / $avg['time']);
        }

        return 3; // TODO param!!
    }

}