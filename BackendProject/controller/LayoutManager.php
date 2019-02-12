<?php

namespace eTorn\Controller;

use eTorn\Bbdd\LayoutDao;
use eTorn\Models\Layout;
use eTorn\Models\TurnsScreen;
use eTorn\Models\TotemScreen;

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

	public function findById($id)
	{
		return $this->layoutsDao->findById($id);
	}

	public function findAllTurnsScreen()
	{
		return $this->layoutsDao->findAllTurnsScreen();
	}

	public function findAllTotemScreen()
	{
		return $this->layoutsDao->findAllTotemScreen();
	}

	public function save($body, $type) 
	{
		$l = null;

		switch ($type) {
			case 'TURNSCREEN':
				$l = new TurnsScreen();
				break;

			case 'TOTEMSCREEN':
				$l = new TotemScreen();
				break;

			default:
				return array( 'done' => false );
		}

		$l->setName($body->name);
		$l->setDescription($body->description);
		$l->setLayout(\json_encode($body->layout));

		return array(
			'done' => $this->layoutsDao->save($l)
		);
	}
}