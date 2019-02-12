<?php

namespace eTorn\Models;

class BucketQueue implements JsonSerializable {

    private $id;
    private $idStore;
    private $idDestinationQueue;
    private $type;
    private $priority;

    public function __construct() {}

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
    public function getIdDestinationQueue()
    {
        return $this->idDestinationQueue;
    }

    /**
     * @return mixed
     */
    public function getIdStore()
    {
        return $this->idStore;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $idDestinationQueue
     */
    public function setIdDestinationQueue($idDestinationQueue)
    {
        $this->idDestinationQueue = $idDestinationQueue;
    }

    /**
     * @param mixed $idStore
     */
    public function setIdStore($idStore)
    {
        $this->idStore = $idStore;
    }

    /**
     * @param mixed $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
            'idStore' => $this->idStore,
            'idDestinationQueue' => $this->idDestinationQueue,
            'type' => $this->type,
            'priority' => $this->priority
        );
    }
}