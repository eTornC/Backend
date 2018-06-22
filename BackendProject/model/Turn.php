<?php

class Turn implements JsonSerializable {

    private $id;
    private $number;
    private $idBucket;
    private $idQueue;
    private $dateTurn;
    private $state;
    private $idUser;

    function __construct() {
    }

    /**
     * @return mixed
     */
    public function getIdQueue()
    {
        return $this->idQueue;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDateTurn()
    {
        return $this->dateTurn;
    }

    /**
     * @return mixed
     */
    public function getIdBucket()
    {
        return $this->idBucket;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idQueue
     */
    public function setIdQueue($idQueue)
    {
        $this->idQueue = $idQueue;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $dateTurn
     */
    public function setDateTurn($dateTurn)
    {
        $this->dateTurn = $dateTurn;
    }

    /**
     * @param mixed $idBucket
     */
    public function setIdBucket($idBucket)
    {
        $this->idBucket = $idBucket;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize() {

        return array(
            'id' => $this->id,
            'number' => $this->number,
            'idBucket' => $this->idBucket,
            'idQueue' => $this->idQueue,
            'dateTurn' => $this->dateTurn,
            'state' => $this->state
        );
    }
}