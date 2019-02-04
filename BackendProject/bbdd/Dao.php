<?php

namespace eTorn\Bbdd;

use eTorn\Bbdd\MySqliSingleton;

abstract class Dao {

    private $DAO;

    public function __construct () {

        $this->DAO = MySqliSingleton::getMySqliInstance();

        if ($this->DAO->connect_errno){
            echo "Connect failed: " . mysqli_connect_error();
            exit();
        }
    }

    public function query($sql){
        $result = $this->DAO->query($sql);
        return $result;
    }

    public function getDao(){
        return $this->DAO;
    }

    public function close() {
        return $this->DAO->close();
    }

    public abstract function findAll();

    public abstract function findById($id);

    public abstract function findByProperty($property, $value);

    public abstract function save($object);

    public abstract function update($object);

    public abstract function delete($id);
}


?>