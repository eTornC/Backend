<?php

require dirname(__FILE__) . '/../bbdd/StoreDao.php';
require dirname(__FILE__) . '/ImageAlmacenator.php';

class StoreManager {

    private $storeDao;

    function __construct() {
        $this->storeDao = new StoreDao();
    }

    public function findAll() {
        return $this->storeDao->findAll();
    }

    public function findById($id) {
        try {
            $id = (int) htmlentities(addslashes($id));
            return $this->storeDao->findById($id);
        } catch (Exception $e) {
            return array();
        }
    }

    public function delete($id) {
        try {
            $id = (int) htmlentities(addslashes($id));
            return array("done" => $this->storeDao->delete($id));
        } catch (Exception $e) {
            return array("done" => false);
        }
    }

    public function save($name, $imageFile) {

        $name = htmlentities(addslashes($name));

        $store = new Store();
        $store->setName($name);

        $imageAlmacenator = new ImageAlmacenator($imageFile['name'], $imageFile['tmp_name'], $imageFile['size'],
            $imageFile['type'], ConstantsPaths::PATH_IMAGES);

        $imageAlmacenator->setImageName($store->getName());

        $store->setPhotopath($imageAlmacenator->getTargetPath());

        if (!$imageAlmacenator->uploadPhoto()) {
            return array('done' => false);
        }



        return array('done' => $this->storeDao->save($store));
    }

    public function update($body, $id){
        try {
            $name = htmlentities(addslashes($body->name));
            $id = (int) htmlentities(addslashes($id));

            $store = new Store();
            $store->setName($name);
            $store->setId($id);

            return array('done' => $this->storeDao->update($store));

        } catch (Exception $e) {
            return array('done' => false);
        }
    }

}


?>