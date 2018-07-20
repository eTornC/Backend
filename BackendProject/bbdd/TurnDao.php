<?php

class TurnDao extends Dao {

    public function __construct() {
        parent::__construct();
    }

    public function findAll() {
        return $this->findByProperty(1, 1);
    }

    public function findById($id) {
        return $this->findByProperty("ID", $id);
    }

    public function findByProperty($property, $value) {

        $query = "SELECT * FROM TURN WHERE $property = " . (is_string($value) ? "'$value'" : $value);

        $result = parent::query($query);

        $toReturn = array();

        while($row = $result->fetch_assoc()) {

            $turn = new Turn();
            $turn->setId($row['ID']);
            $turn->setNumber($row['NUMBER']);
            $turn->setIdBucket($row['ID_BUCKET']);
            $turn->setIdUser($row['ID_USER']);
            $turn->setIdQueue($row['ID_QUEUE']);
            $turn->setDateTurn($row['DATE_TURN']);
            $turn->setState($row['STATE']);

            $toReturn[] = $turn;
        }

        return $toReturn;
    }

    public function save($turn) {

        if ($turn instanceof Turn) {

            $query = "INSERT INTO TURN (NUMBER, ID_BUCKET, ID_USER, ID_QUEUE, DATE_TURN, STATE) VALUES " .
                        "(" . $turn->getNumber() . ", " . $turn->getIdBucket() . ", " . $turn->getIdUser() .
                        ", " . $turn->getIdQueue() . ", NOW(), '" . $turn->getState() . "')";

            return parent::query($query);
        }

        return false;
    }

    public function saveHourTurn(Turn $turn) {

        $query = "INSERT INTO TURN (ID_BUCKET, ID_USER, DATE_TURN, STATE) VALUES " .
            "(" . $turn->getIdBucket() . ", " . $turn->getIdUser() . ", NOW(), '"
            . $turn->getState() . "')";

        return parent::query($query);
    }

    public function update($turn) {

        if ($turn instanceof Turn) {

            $query = "UPDATE TURN SET STATE = '" . $turn->getState() . "' WHERE ID = " . $turn->getId();

            return parent::query($query);
        }

        return false;
    }

    public function delete($id) {

        $query = "DELETE FROM TURN WHERE ID = " . $id;

        return parent::query($query);
    }

    public function findByIdQueue($idQueue) {

        return $this->findByProperty('ID_QUEUE', $idQueue);
    }

    public function getActualTurn($idStore) {

        $query = "SELECT T.* 
                  FROM TURN T JOIN QUEUE Q ON T.ID_QUEUE=Q.ID 
                  WHERE T.STATE = 'ATTENDING' AND Q.ID_STORE = $idStore";

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
            $turn->setIdTill($row['ID_TILL']);

            return $turn;
        }

        return null;
    }

    public function getListNextsTurns($idStore) {

        $results = array();

        $query = "  SELECT T.*
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

}