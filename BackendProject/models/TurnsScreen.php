<?php

namespace eTorn\Models;

class TurnsScreen extends Layout 
{ 
	public function __construct()
	{
		parent::__construct();
		parent::setType('turnsscreens');
	}
}