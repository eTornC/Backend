<?php

namespace eTorn\Controller;

use eTorn\Bbdd\ConfigDao;
use eTorn\Models\Config;
use Illuminate\Database\Capsule\Manager as DB;

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
        $config = new Config();
        $config->setKey($body->key);
        $config->setValue($body->value);

        return array('done', $this->configDao->save($config));
    }

    public function update($body, $id) {
        $config = new Config();
        $config->setId($id);
        $config->setKey($body->key);
        $config->setValue($body->value);

        return array('done', $this->configDao->update($config));
    }

    public function delete($id)
    {
        return [
            'done', $this->configDao->delete($id)
        ];
    }

    public function updateConfigs($body)
    {
        Config::truncate();

        $params = $body->data->params;

        DB::beginTransaction();

        try {
            foreach ($params as $key => $value) {

                $config = new Config();
                $config->key = $key;
                $config->value = $value;

                $result = $config->save();

                if (!$result) {
                    throw new \Exception('error');
                }
            }

            DB::commit();

            return [
                'done' => true
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'done' => false
            ];
        }
    }
}

?>