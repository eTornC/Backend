<?php

require dirname(__FILE__) . '/../bbdd/TurnDao.php';

class TurnManager {

    private $turnDao;

    public function __construct() {
        $this->turnDao = new TurnDao();
    }

    public function findAll() {
        return $this->turnDao->findAll();
    }

    public function findByIdStoreAndIdQueue($idStore, $idQueue) {
        return $this->turnDao->findByIdQueue($idQueue);
    }

    public function findById($idTurn) {
        return $this->turnDao->findById($idTurn);
    }

    public function save($body, $idStore, $idQueue) {

        $turn = new Turn();

        //$turn->setNumber($turnNumber);
        $turn->setIdQueue($idQueue);
        $turn->setState('WAITING');
        $turn->setIdUser(0); // Body?

        return array('done' => $this->turnDao->save($turn));
    }

    public function update($body, $idStore, $idQueue, $idTurn) {

        $turn = new Turn();
        $turn->setId($idTurn);
        $turn->setState($body->state);
        $turn->setIdQueue($idQueue);

        return array('done' => $this->turnDao->update($turn));
    }

    public function delete($idTurn) {
        return array('done' => $this->turnDao->delete($idTurn));
    }


}