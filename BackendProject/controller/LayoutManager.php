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
		try {
			$l = new Layout();

			if ($type === 'TURNSCREEN' || $type === 'TOTEMSCREEN') {
				$l->type = $type;
			} else {
				return array( 'done' => false );
			}
	
			$l->name = $body->name;
			$l->description = $body->description;
			$l->layout = $body->layout;
	
			return array(
				'done' => $this->layoutsDao->save($l)
			);
		} catch (\Exception $e) {
			return array(
			    'done' => false,
				'err' => $e->getMessage(),
			);
		}
		
	}
}