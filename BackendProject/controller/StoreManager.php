<?php

namespace eTorn\Controller;

use eTorn\Bbdd\StoreDao;
use eTorn\Models\Store;
use eTorn\Models\Queue;
use eTorn\Constants\ConstantsPaths;

class StoreManager {

    private $storeDao;

    function __construct() {
        $this->storeDao = new StoreDao();
    }

    public function findAll() {
        return $this->storeDao->findAll();
    }

    public function findById($id) {
        $store = $this->storeDao->findById($id);

        if (!$store) {
            return [
                'err' => 'Store not found'
            ];
        }

        return $store;
    }

    public function delete($id) {
        $store = $this->storeDao->findById($id);

        if (!$store) {
            return [
                'done' => false
            ];
        }

        return array("done" => $this->storeDao->delete($store));
    }

    public function save($name, $imageFile) {

        $store = new Store();
        $store->name = $name;

        try{
            $imagePath = ImageAlmacenator::getInstance()->saveImage($imageFile);
            $store->photo_path = $imagePath;
            
            $store->save();

            $bucketQueue = new Queue();
            $bucketQueue->type = 'BUCKETS';

            $store->queues()->save($bucketQueue);

            return [
                'done' => true
            ];

        } catch (\Exception $e) {
            Logger::getInstance()->logError('StoreManager@save - ' . $e->getMessage());
            return array('done' => false);
        }
    }

    public function update($body, $id)
    {
        try {

            $store = Store::find($id);

            if (!$store) {
                return [
                    'done' => false
                ];
            }

            if (!array_key_exists('name', (array) $body)) {
                return [
                    'done' => false
                ];
            }

            $store->name = $body->name;

            return [
                'done' => $this->storeDao->save($store),
                'store' => $store
            ];

        } catch (\Exception $e) {
            Logger::error('StoreManager@update - ' . $e->getMessage());
            return array('done' => false);
        }
    }
}
