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
	public function delete($id){
        return $this->layoutsDao->delete($id);
    }

    public function update($body,$id){
        $l = $this->layoutsDao->findById($id);

        if (!$l) {
            return [
                'done' => false
            ];
        }

        try {

            if(!$body){
                return [
                    'done' => false
                ];
            }
            if (array_key_exists('name', (array) $body)) {
                $l->name = $body->name;
            }
            if (array_key_exists('description', (array) $body)) {
                $l->description = $body->description;
            }
            if (array_key_exists('layout', (array) $body)) {
                $l->layout = $body->layout;
            }

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

	public function save($body, $type) 
	{
		try {
			$l = new Layout();

			if ($type === 'TURNSCREEN' || $type === 'TOTEMSCREEN' || $type === 'TEMPLATE') {
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