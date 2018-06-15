<?php

    require dirname(__FILE__) . '/../constants/ConstantsDB.php';
    require dirname(__FILE__) . '/../model/Store.php';
    require dirname(__FILE__) . '/../model/Queue.php';

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

        public abstract function save($queue);

        public abstract function update($object);

        public abstract function delete($id);
    }


?>