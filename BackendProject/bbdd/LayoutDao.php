<?php

namespace eTorn\Bbdd;

use eTorn\Bbdd\Dao;

use eTorn\Models\Layout;

class LayoutDao extends Dao {

	public function __construct() {
        parent::__construct();
    }

    public function findAll() 
    {
        return $this->findByProperty(1, 1);
    }

    public function findByProperty($property, $value) {

        $query = "SELECT * FROM LAYOUTS WHERE $property = " . (is_string($value) ? "'$value'" : $value);

        $result = parent::query($query);

        $toReturn = array();

        while($row = $result->fetch_assoc()) {

            $layout = new Layout();
            $layout->setId($row['id']);
            // TODO 

            $toReturn[] = $layout;
        }

        return $toReturn;

    }

}