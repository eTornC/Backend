<?php

class TillDao extends Dao {

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

        $tills = array();

        $query = "SELECT * FROM TILL WHERE $property = " . (is_string($value) ? "'$value'" : $value);

        $result = $this->query($query);

        while ($row = $result->fetch_assoc()) {

            $till = new Till();
            $till->setId($row['ID']);
            $till->setName($row['NAME']);
            $till->setActive($row['ACTIVE']);
            $till->setIdStore($row['ID_STORE']);

            $tills[] = $till;
        }

        return $tills;
    }

    public function save($till) {

        if ($till instanceof Till) {

            $query = "INSERT INTO TILL (ID_STORE, NAME, ACTIVE, DATE_CREATED) VALUES (" . $till->getIdStore() .
                        ", '" . $till->getName() . "', FALSE, NOW())";

            return parent::query($query);
        }

        return false;
    }

    public function update($till) {

        if ($till instanceof Till) {

            $query = "UPDATE TILL SET NAME='" . $till->getName() . "', ACTIVE=" . $till->getActive() .
                        " WHERE ID = " . $till->getId();

            return parent::query($query);
        }

        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM TILL WHERE ID = $id";
        return parent::query($query);
    }
}