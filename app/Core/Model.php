<?php

namespace Application\Core;

use Application\Core\Config;

/**
 * Содержит основную логику приложения - вычисления, запросы к БД и обработку полученных из БД данных.
 * Все то, чего не должно быть в Роутере, Контроллере или Представлении :) 
 */
class Model {

    protected $errors = array();
    protected $successful = array();
    protected $dbh;
    
    public function __construct()
    {
        $constants = ConfigModel::CONSTANTS;
    }

    public function getErrors() {
        return $this->errors;
    }
    
    public function getSuccessful()
    {
        return $this->successful;
    }
    
    protected function getConstants($name)
    {
        $config = Config::getInstance();
        $constants = $config->getConfig(Config::CONSTANTS);
        return $constants[$name];
    }
    
    // проверяет существование элемента
    protected function checkID($id, $tableName)
    {
        $query = $this->dbh->query("SELECT * FROM `$tableName` WHERE `id` = ?;", 'rowCount', '', array($id));
        return $query === 1;
    }

}
