<?php

namespace eTorn\Models;

class Store implements \JsonSerializable {

    private $id;
    private $name;
    private $date;
    private $photopath;

    function __construct() {}

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getPhotopath() {
        return $this->photopath;
    }

    /**
     * @param mixed $photopath
     */
    public function setPhotopath($photopath) {
        $this->photopath = $photopath;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize() {
        return array (
            'id' => $this->id,
            'name' => $this->name,
            'date_created' => $this->date,
            'photopath' => $this->photopath
        );
    }
}