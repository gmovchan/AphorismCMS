<?php

class Model
{
    
}

/**
 * Объект класса подключается к БД и работает с запросами
 * Новый уровень абстракции для работы с БД и запросами необходим, чтобы
 * если изменится способ подключения и настройки, то достаточно было бы изменить
 * только этот класс
 */
class MysqlModel extends Model
{

    //хранит подключение к БД для доступа к нему из методов класса
    private $dbh;
    private $config_data;

    /**
     * 
     * @param type $settingValue
     */
    public function __construct($settingValue)
    {
        // получает настройки для соединения с БД
        $mysqlConfig = ConfigModel::getInstance();
        $this->config_data = $mysqlConfig->getConfig($settingValue);

        $this->connect();
    }

    private function connect()
    {
        // отлов ошибок подключения к БД
        $this->dbh = new PDO('mysql:host=' . $this->config_data['host'] . ';dbname=' .
                $this->config_data['db'] . ';charset=utf8', $this->config_data['user'], $this->config_data['password']);
        // требуется чтобы PDO сообщало об ошибке и прерывало выполнение скрипта
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * запрос к БД
     * без понятия зачем тут нужен новый уровень абстракции
     * $section_name принимает массив с параметрами для подготавливаемого 
     * запроса с неименованными псевдопеременными для защиты от инъекций
     */
    public function query($query, $typeQuery = null, $num = null, array $query_param = array())
    {
        if ($q = $this->dbh->prepare($query)) {
            switch ($typeQuery) {
                case 'num_row':
                    $q->execute($query_param);
                    return $q->rowCount();
                    break;

                case 'result':
                    $q->execute($query_param);
                    return $q->fetchColumn($num);
                    break;

                // возвращает одну строку в виде массива, где ключ - имя столбца
                case 'accos':
                    $q->execute($query_param);
                    return $q->fetch(PDO::FETCH_ASSOC);
                    break;

                // получает все строки в виде массива
                case 'fetchAll':
                    $q->execute($query_param);
                    return $q->fetchAll();
                    break;

                case 'none':
                    $q->execute($query_param);
                    return $q;
                    break;

                default:
                    // выкидывает исключение и завершает скрипт, если не найден переданный тип SQL запроса
                    throw new \Exception("Ошибка при указании типа SQL запроса");
            }
        }
    }

    public function getLastInsertId()
    {
        return intval($this->dbh->lastInsertId());
    }

}

/**
 * синглтон для хранения и получения настроек
 */
class ConfigModel extends Model
{

    private $configArray;
    private static $instance;
    // путь к файлу с конфигурациями
    private $configFilePath;
    // вариант настроек для подключения БД
    const UNMARRIED = 1;

    public function __construct()
    {
        $this->configFilePath = __DIR__ . '/app.ini';
        $this->configArray = $this->getAllConfig();
    }

    public static function getInstance()
    {
        // проверяет, был ли уже создан объект и если нет, то создает его
        if (empty(self::$instance)) {
            // класс с закрытым конструктором может сам
            // себя создать
            self::$instance = new ConfigModel();
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

            default:
                $this->ensure(false, "Переданная константа для получения настроек не задана");
                break;
        }
    }

    // получает массив с содержимым файла конфигурации
    private function getAllConfig()
    {
        $this->ensure(file_exists($this->configFilePath), "Файл с настройками не найден");

        $config_array = parse_ini_file($this->configFilePath, true);

        return $config_array;
    }

    // централизованная проверка условия и вызов исключения
    private function ensure($expr, $message)
    {
        
        if (!$expr) {
            throw new Exception($message);
        }
    }

}
