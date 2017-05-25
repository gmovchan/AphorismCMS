<?php

namespace Application\Core;

use Application\Core\Model;

class Config extends Model
{

    private $configArray;
    private static $instance;
    // путь к файлу с конфигурациями
    private $configFilePath;
    // вариант настроек для подключения БД
    const UNMARRIED = 1;
    const CONSTANTS = 2;

    public function __construct()
    {
        $this->configFilePath = __DIR__ . '/../configs/app.ini';
        $this->configArray = $this->getAllConfig();
    }

    public static function getInstance()
    {
        // проверяет, был ли уже создан объект и если нет, то создает его
        if (empty(self::$instance)) {
            // класс с закрытым конструктором может сам
            // себя создать
            self::$instance = new Config();
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
            case self::UNMARRIED:

                $this->ensure(isset($this->configArray['unmarried']), "Настройки для константы STK не найдены");

                return $this->configArray['unmarried'];
                break;
            
            case self::CONSTANTS:

                $this->ensure(isset($this->configArray['constants']), "Настройки для константы CONSTANTS не найдены");

                return $this->configArray['constants'];
                break;

            default:
                $this->ensure(false, "Переданная константа для получения настроек не задана");
                break;
        }
    }

    // получает массив с содержимым файла конфигурации
    private function getAllConfig()
    {
        $this->ensure(file_exists($this->configFilePath), "Файл с настройками не найден");       
        // по возможности сохраняет тип значения
        $config_array = parse_ini_file($this->configFilePath, true, $scanner_mode = INI_SCANNER_TYPED);
        return $config_array;
    }

}
