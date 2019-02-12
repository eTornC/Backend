<?php

namespace eTorn\Bbdd;

use eTorn\Bbdd\Dao;

use eTorn\Models\Layout;

class LayoutDao extends Dao {

    public function __construct() 
    {
        parent::__construct();
    }

    public function findAll() 
    {
        return $this->findByProperty(1, 1);
    }

    public function findAllTurnsScreen() 
    {
        return $this->findByProperty('TYPE', 'TURNSCREEN');
    }
    
    public function findAllTotemScreen() 
    {
        return $this->findByProperty('TYPE', 'TOTEMSCREEN');
    }

    public function findByProperty($property, $value) 
    {
        $query = "SELECT * FROM LAYOUTS WHERE $property = " . (is_string($value) ? "'$value'" : $value);

        $result = parent::query($query);

        if (!$result) {
            return false;
        }

        $toReturn = array();

        while($row = $result->fetch_assoc()) {

            $layout = new Layout();
            $layout->setId($row['ID']);
            $layout->setName($row['NAME']);
            $layout->setDescription($row['DESCRIPTION']);
            $layout->setLayout($row['LAYOUT']);
            $layout->setType($row['TYPE']);

            $toReturn[] = $layout;
        }

        return $toReturn;
    }

    public function findById($id) 
    {
        return $this->findByProperty('ID', $id);
    }

    public function save($l) 
    {
        $query = "INSERT INTO LAYOUTS (NAME, DESCRIPTION, LAYOUT, TYPE) VALUES ('" 
                    . $l->getName() . "', '" . $l->getDescription() . "', '" . 
                    $l->getLayout() . "', '" . $l->getType() . "')";
        
        return parent::query($query);
    }

    public function update($object) {}

    public function delete($id) {}

}