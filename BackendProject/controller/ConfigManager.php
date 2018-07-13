<?php

require dirname(__FILE__) . '../bbdd/ConfigDao.php';

class ConfigManager {

    private $configDao;

    public function __construct() {
        $this->configDao = new ConfigDao();
    }

    public function findAll() {
        return $this->configDao->findAll();
    }

    public function findById($id) {
        return $this->configDao->findById($id);
    }

    public function findByKey($key) {
        return $this->configDao->findByKey($key);
    }

    public function save($body) {
        $config = new \eTorn\models\Config();
        $config->setKey($body->key);
        $config->setValue($body->value);

        return array('done', $this->configDao->save($config));
    }

    public function update($body, $id) {
        $config = new \eTorn\models\Config();
        $config->setId($id);
        $config->setKey($body->key);
        $config->setValue($body->value);

        return array('done', $this->configDao->update($config));
    }

    public function delete($id) {
        return array('done', $this->configDao->delete($id));
    }
}

?>