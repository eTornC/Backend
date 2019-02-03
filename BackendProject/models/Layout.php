<?php

namespace eTorn\Models;

class Layout {
	
	private $id;
	private $name;
	private $description;
	private $image;
	private $layout;
	private $type;

	public function __construct() {

	}

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
	 * Get the value of image
	 */ 
	public function getImage()
	{
		return $this->image;
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
	 * Set the value of image
	 *
	 * @return  self
	 */ 
	public function setImage($image)
	{
		$this->image = $image;

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
}