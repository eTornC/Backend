<?php

class Bucket implements JsonSerializable {

    private $id;
    private $idQueue;
    private $hourFinal;
    private $quantity;

    function __construct()
    {
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
    public function getHourFinal()
    {
        return $this->hourFinal;
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
     * @param mixed $hourFinal
     */
    public function setHourFinal($hourFinal)
    {
        $this->hourFinal = $hourFinal;
    }

    /**
     * @param mixed $idQueue
     */
    public function setIdQueue($idQueue)
    {
        $this->idQueue = $idQueue;
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
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return '';
    }
}