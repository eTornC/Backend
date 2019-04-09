<?php

namespace eTorn\Bbdd;

use eTorn\Models\Store;

class StoreDao {

    public function findAll() {
        return Store::all();
    }

    public function findById($id) {
        return Store::where('id', $id)->first();
    }

    public function findByProperty($property, $value) {
        return Store::where($property, $value)->get();
    }

    public function save(Store $store) {
        return $store->save();
    }

    public function delete(Store $store)
    {
        return $store->delete();
    }
}