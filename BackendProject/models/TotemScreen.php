<?php

namespace eTorn\Models;

class TotemScreen extends Layout 
{ 
	public function __construct()
	{
		parent::__construct();
		parent::setType('TOTEMSCREEN');
	}
}