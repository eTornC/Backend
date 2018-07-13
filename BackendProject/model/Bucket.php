<?php

class Bucket implements JsonSerializable {

    private $id;
    private $idBucketQueue;
    private $hourStart;
    private $hourFinal;
    private $quantity;
    private $dateCreated;

    function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @return mixed
     */
    public function getHourFinal()
    {
        return $this->hourFinal;
    }

    /**
     * @return mixed
     */
    public function getHourStart()
    {
        return $this->hourStart;
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
    public function getIdBucketQueue()
    {
        return $this->idBucketQueue;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @param mixed $hourFinal
     */
    public function setHourFinal($hourFinal)
    {
        $this->hourFinal = $hourFinal;
    }

    /**
     * @param mixed $hourStart
     */
    public function setHourStart($hourStart)
    {
        $this->hourStart = $hourStart;
    }

    /**
     * @param mixed $idBucketQueue
     */
    public function setIdBucketQueue($idBucketQueue)
    {
        $this->idBucketQueue = $idBucketQueue;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
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
            'idBucketQueue' => $this->idBucketQueue,
            'hourStart' => $this->hourStart,
            'hourFinal' => $this->hourFinal,
            'quantity' => $this->quantity,
            'dateCreated' => $this->dateCreated
        );
    }
}