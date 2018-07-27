<?php

use \eTorn\models\Config;

class ConfigDao extends Dao {

    public function findAll() {
        return $this->findByProperty(1, 1);
    }

    public function findById($id) {
        return $this->findByProperty('ID', $id);
    }

    public function findByProperty($property, $value) {

        $query = "SELECT * FROM CONFIG WHERE $property = " . (is_string($value) ? "'$value'" : $value);

        $result = parent::query($query);

        $toReturn = array();

        while($row = $result->fetch_assoc()) {

            $config = new Config();
            $config->setId($row['ID']);
            $config->setKey($row['NAME']);
            $config->setValue($row['VALUE']);

            $toReturn[] = $config;
        }

        return $toReturn;
    }

    public function save($object) {

        if ($object instanceof Config) {

            $query = "INSERT INTO CONFIG (NAME, VALUE) VALUES ('" . $object->getKey() . "', '" .
                        $object->getValue() . "')";

            return parent::query($query);
        }

        return false;
    }

    public function update($object) {

        if ($object instanceof Config) {

            $query = "UPDATE CONFIG SET VALUE='" . $object->getValue() ."' WHERE ID = " . $object->getId();

            return parent::query($query);
        }

        return false;
    }

    public function updateByKey(Config $config) {
        $query = "UPDATE CONFIG SET VALUE='" . $config->getValue() ."' WHERE NAME = '" . $config->getKey() . "'";
        return parent::query($query);
    }

    public function delete($id) {
        $query = "DELETE FROM CONFIG WHERE ID = $id";
        return parent::query($query);
    }

    public function findByKey($key) {
        return $this->findByProperty('NAME', $key)[0];
    }
}


?>