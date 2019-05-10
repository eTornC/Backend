<?php

namespace eTorn\Bbdd;

use eTorn\Models\Publicity;

class PublicityDao {

    public function findAll() 
    {
        return Publicity::all();
    }

    public function findAllTurnsScreen() 
    {
        return Publicity::where('TYPE', 'TURNSCREEN')->get();
    }
    
    public function findAllTotemScreen() 
    {
        return Publicity::where('TYPE', 'TOTEMSCREEN')->get();
    }

    public function findByProperty($property, $value) 
    {
        return Publicity::where($property, $value)->get();
    }

    public function findById($id) 
    {
        return Publicity::find($id);
    }

    public function save(Publicity $p)
    {
        return $p->save();
    }


    public function delete(Publicity $publicity) {
        return $publicity->delete();
    }

}