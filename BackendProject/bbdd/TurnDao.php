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
}