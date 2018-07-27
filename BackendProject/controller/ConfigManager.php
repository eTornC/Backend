<?php

//require dirname(__FILE__) . '/../bbdd/ConfigDao.php';

use \eTorn\models\Config;

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

    public function updateConfigs($body) {

        $minDurationConfig = new Config();
        $minDurationConfig->setKey('MIN_DURATION_BUCKETS');
        $minDurationConfig->setValue($body->data->params->MIN_DURATION_BUCKETS);

        $hourStartConfig = new Config();
        $hourStartConfig->setKey('HOUR_START_ALL_BUCKETS');
        $hourStartConfig->setValue($body->data->params->HOUR_START_ALL_BUCKETS);

        $hourFinalConfig = new Config();
        $hourFinalConfig->setKey('HOUR_FINAL_ALL_BUCKETS');
        $hourFinalConfig->setValue($body->data->params->HOUR_FINAL_ALL_BUCKETS);

        return array('done' => $this->configDao->updateByKey($hourFinalConfig)
                && $this->configDao->updateByKey($hourStartConfig)
                && $this->configDao->updateByKey($minDurationConfig)
        );
    }
}

?>