<?php

namespace eTorn\Bbdd;

use eTorn\Models\Queue;
use eTorn\Models\Store;
use eTorn\Models\Turn;

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
			->max('turns.number');

		if ($turn) {
			return $turn + 1;
		}

		return 1;
	}

    public function getListNextsTurns(Store $store)
	{
		$queue = $store->queues()->first();
			/*
			return $queue->buckets()
				->leftJoin('turns', 'turns.id_bucket', '=', 'buckets.id')
				->where('turns.state' , '=', 'WAITING')
				->orderBy('turns.created_at', 'asc')
				->select('turns.*')
				->get();
			*/

		return Turn::where('turns.state' , '=', 'WAITING')
			->join('buckets', 'turns.id_bucket', '=', 'buckets.id')
			->where('buckets.id_queue', '=', $queue->id)
			->orderBy('turns.created_at', 'asc')
			->get();

    }

    public function getFirstTurnInQueue($idQueue) {

        $query = "SELECT * FROM TURN WHERE ID_QUEUE = $idQueue AND NUMBER = (SELECT MIN(NUMBER) " .
                    "FROM TURN WHERE ID_QUEUE = $idQueue AND STATE LIKE 'WAITING');";

        $result = $this->query($query);

        if ($row = $result->fetch_assoc()) {

            $turn = new Turn();
            $turn->setId($row['ID']);
            $turn->setNumber($row['NUMBER']);
            $turn->setIdBucket($row['ID_BUCKET']);
            $turn->setIdUser($row['ID_USER']);
            $turn->setIdQueue($row['ID_QUEUE']);
            $turn->setDateTurn($row['DATE_TURN']);
            $turn->setState($row['STATE']);

            return $turn;
        }

        return null;
    }

    public function getLastTurn($idQueue) {

        $query = "SELECT * FROM TURN WHERE ID_QUEUE = $idQueue AND NUMBER = (SELECT MAX(NUMBER) " .
            "FROM TURN WHERE ID_QUEUE = $idQueue);";

        $result = $this->query($query);

        $turn = new Turn();

        if ($row = $result->fetch_assoc()) {

            $turn->setId($row['ID']);
            $turn->setNumber($row['NUMBER']);
            $turn->setIdBucket($row['ID_BUCKET']);
            $turn->setIdUser($row['ID_USER']);
            $turn->setIdQueue($row['ID_QUEUE']);
            $turn->setDateTurn($row['DATE_TURN']);
            $turn->setState($row['STATE']);

        } else {
            $turn->setNumber(0);
        }

        return $turn;
    }

    public function getMobileTurnsToUpdate(Bucket $bucket) {

        $query = "SELECT * FROM TURN WHERE ID_BUCKET = " . $bucket->getId() . " AND STATE = 'WAITING'";

        $results = array();

        $res = $this->query($query);

        while ($row = $res->fetch_assoc()) {

            $turn = new Turn();
            $turn->setId($row['ID']);
            $turn->setNumber($row['NUMBER']);
            $turn->setIdBucket($row['ID_BUCKET']);
            $turn->setIdUser($row['ID_USER']);
            $turn->setIdQueue($row['ID_QUEUE']);
            $turn->setDateTurn($row['DATE_TURN']);
            $turn->setState($row['STATE']);

            $results[] = $turn;
        }

        return $results;
    }

}