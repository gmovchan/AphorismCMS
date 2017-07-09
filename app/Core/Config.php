<?php

namespace Application\Core;

use Application\Core\ErrorHandler;

class Config
{

    private $configArray;
    private static $instance;
    // путь к файлу с конфигурациями
    private $configFilePath;

    // вариант настроек для подключения БД
    const DB = 1;
    const CONSTANTS = 2;

    private function __construct()
    {
        $this->configFilePath = __DIR__ . '/../../configs/app.ini';
        $this->configArray = $this->getAllConfig();
    }

    public static function getInstance()
    {
        // проверяет, был ли уже создан объект и если нет, то создает его
        if (empty(self::$instance)) {
            // класс с закрытым конструктором может сам
            // себя создать
            self::$instance = new self();
        }
        // возвращает ссылку на созданный объект
        return self::$instance;
    }

    /**
     * возвращает настройки для подключения к БД, необходимо передать ключ секции настроек
     * @param type $settingValue значение для которого требуется получить настройки 
     * @return type массив с данными из заданной секции настроек
     */
    public function getConfig($settingValue)
    {

        switch ($settingValue) {
            case self::DB:
                
                // проверяет на каком сервере запущен скрипт и исходя из этого 
                // возвращает соответствующие настройки для БД
                $appStatus = $this->getConfigElement(self::CONSTANTS, 'app_in_production');
                
                if ($appStatus === 0) {
                    ErrorHandler::ensure(isset($this->configArray['test_db']), "Настройки для константы \"test_db\" не найдены");
                    return $this->configArray['test_db'];
                    break;
                }
                
                if ($appStatus === 1) {
                    ErrorHandler::ensure(isset($this->configArray['vds_db']), "Настройки для константы \"vds_db\" не найдены");
                    return $this->configArray['vds_db'];
                    break;
                }


            case self::CONSTANTS:

                ErrorHandler::ensure(isset($this->configArray['constants']), "Настройки для константы CONSTANTS не найдены");

                return $this->configArray['constants'];
                break;

            default:
                ErrorHandler::ensure(false, "Переданная константа для получения настроек не задана");
                break;
        }
    }

    public function getConfigElement($arrayName, $elemName)
    {
        $configArray = $this->getConfig($arrayName);

        if (isset($configArray[$elemName])) {
            return $configArray[$elemName];
        } else {
            // TODO: добавить функцию записи в лог
            return null;
        }
    }

    // получает массив с содержимым файла конфигурации
    private function getAllConfig()
    {
        ErrorHandler::ensure(file_exists($this->configFilePath), "Файл с настройками не найден");
        // по возможности сохраняет тип значения
        $config_array = parse_ini_file($this->configFilePath, true, $scanner_mode = INI_SCANNER_TYPED);
        return $config_array;
    }

}
