<?php

namespace eTorn\Bbdd;

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

    public function getActualTurn($idStore)
    {
        return Turn::where('id_store', $idStore)
            ->where('state', 'ATTENDING')
            ->get();
    }

    public function getListNextsTurns($idStore) {

        $results = array();

        $query = "  SELECT T.*, Q.QUEUE_TYPE AS 'TYPE'
                    FROM TURN T JOIN QUEUE Q ON T.ID_QUEUE=Q.ID
                    WHERE T.STATE LIKE 'WAITING' AND Q.ID_STORE = $idStore 
                    ORDER BY Q.PRIORITY, T.DATE_TURN, T.ID;
                    ";

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
            $turn->setType($row['TYPE']);

            $results[] = $turn;
        }

        return $results;
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