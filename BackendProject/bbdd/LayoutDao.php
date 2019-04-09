<?php

namespace eTorn\Bbdd;

use eTorn\Bbdd\Dao;

use eTorn\Models\Layout;

class LayoutDao {

    public function findAll() 
    {
        return Layout::all();
    }

    public function findAllTurnsScreen() 
    {
        return Layout::where('TYPE', 'TURNSCREEN')->get();
    }
    
    public function findAllTotemScreen() 
    {
        return Layout::where('TYPE', 'TOTEMSCREEN')->get();
    }

    public function findByProperty($property, $value) 
    {
        return Layout::where($property, $value)->get();
    }

    public function findById($id) 
    {
        return Layout::find($id);
    }

    public function save(Layout $l) 
    {
        return $l->save();
    }

    public function delete($id) {}

}