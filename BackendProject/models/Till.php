<?php

namespace eTorn\Models;

class Till implements JsonSerializable {

    private $id;
    private $idStore;
    private $name;
    private $active;

    function __construct() {}

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
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
    public function getIdStore()
    {
        return $this->idStore;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $idStore
     */
    public function setIdStore($idStore)
    {
        $this->idStore = $idStore;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
            'name' => $this->name,
            'active' => $this->active
        );
    }
}