<?php

namespace eTorn\Bbdd;

use eTorn\Models\Config;

class ConfigDao {

    public function findAll() {
        return Config::all();
    }

    public function findById($id)
    {
        return Config::find($id);
    }

    public function findByProperty($property, $value)
    {
        return Config::where($property, $value)->get();
    }

    public function save(Config $config)
    {
        return $config->save();
    }

    public function delete($id)
    {
        return $this->findById($id)->delete();
    }

    public function findByKey($key): ?Config
    {
        return Config::where('key', $key)->first();
    }

}
