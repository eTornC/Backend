<?php

/**
 * Created by PhpStorm.
 * User: Josep
 * Date: 11/06/2018
 * Time: 11:48
 */

    require dirname(__FILE__) . '/../constants/ConstantsDB.php';

    require dirname(__FILE__) . '/../model/Store.php';

    abstract class Dao {

        private $DAO;

        public function __construct () {

            $this->DAO = new mysqli(ConstantsDB::DB_SERVER, ConstantsDB::DB_USER, ConstantsDB::DB_PASSWD, ConstantsDB::DB_NAME);

            if ($this->DAO->connect_errno){
                echo "Connect failed: " . mysqli_connect_error();
                exit();
            }

            $this->DAO->set_charset(ConstantsDB::DB_CHARSET);
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