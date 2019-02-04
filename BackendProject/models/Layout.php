<?php

namespace eTorn\Models;

class Layout implements \JsonSerializable {
	
	private $id;
	private $name;
	private $description;
	private $layout;
	private $type;

	public function __construct() {}

	/**
	 * Get the value of name
	 */ 
	public function getId()
	{
		return $this->id;
	}

	public function setId(int $id) 
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * Get the value of name
	 */ 
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get the value of description
	 */ 
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Get the value of layout
	 */ 
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Set the value of name
	 *
	 * @return  self
	 */ 
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Set the value of description
	 *
	 * @return  self
	 */ 
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Set the value of layout
	 *
	 * @return  self
	 */ 
	public function setLayout($layout)
	{
		$this->layout = $layout;

		return $this;
	}

	/**
	 * Get the value of type
	 */ 
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set the value of type
	 *
	 * @return  self
	 */ 
	public function setType($type)
	{
		$this->type = $type;

		return $this;
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
        return array (
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'layout' => \json_decode($this->layout),
            'type' => $this->type,
        );
    }
}