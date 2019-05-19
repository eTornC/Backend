<?php

namespace eTorn\Controller;

use eTorn\Models\Publicity;
use eTorn\Bbdd\PublicityDao;


class PublicityManager {

	private $publicityDao;

	public function __construct()
	{
		$this->publicityDao = new PublicityDao();
	}

	public function findAll() 
	{
		return $this->publicityDao->findAll();
	}

	public function findById($id)
	{
		return $this->publicityDao->findById($id);
	}

	public function update($body,$id){
        $publicity = $this->publicityDao->findById($id);

        if (!$publicity) {
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
                $publicity->name = $body->name;
            }
            if (array_key_exists('description', (array) $body)) {
                $publicity->description = $body->description;
            }
            if (array_key_exists('html', (array) $body)) {
                $publicity->html = $body->html;
            }
            if (array_key_exists('height', (array) $body)) {
                $publicity->height = $body->height;
            }
            if (array_key_exists('width', (array) $body)) {
                $publicity->width = $body->width;
            }

            return array(
                'done' => $this->publicityDao->save($publicity)
            );
        } catch (\Exception $e) {
            return array(
                'done' => false,
                'err' => $e->getMessage(),
            );
        }

    }

	public function save($body)
	{
	    try {
			$p = new Publicity();

			$p->name = $body->name;
			$p->description = $body->description;
            $p->html = $body->html;
            $p->height = $body->height;
            $p->width = $body->width;

            return array(
				'done' => $this->publicityDao->save($p)
			);
		} catch (\Exception $e) {
			return array(
			    'done' => false,
				'err' => $e->getMessage(),
			);
		}
	}

	public function delete($id){
        $publicity = $this->publicityDao->findById($id);

        if (!$publicity) {
            return [
                'done' => false
            ];
        }

        return array("done" => $this->publicityDao->delete($publicity));
    }
}