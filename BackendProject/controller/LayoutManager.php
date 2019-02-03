<?php

namespace eTorn\Controller;

use eTorn\Bbdd\LayoutDao;
use eTorn\Models\Layout;

class LayoutManager {

	private $layoutsDao;

	public function __construct()
	{
		$this->layoutsDao = new LayoutDao();
	}

	public function findAll() 
	{
		return $this->layoutsDao->findAll();
	}
}