<?php

/**
 * Объект класса подключается к БД и работает с запросами
 * Новый уровень абстракции для работы с БД и запросами необходим, чтобы
 * если изменится способ подключения и настройки, то достаточно было бы изменить
 * только этот класс
 */
class MysqlModel
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
        $mysqlConfig = Config::getInstance();
        $this->$config_data = $mysqlConfig->getConfig($settingValue);

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

                // получает только одну строку
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
class Config
{

    private $configArray;
    private static $instance;

    const CONFIG_FILE_PATH = 'app.ini';
    // вариант настроек для подключения БД
    const STK = 1;
    const STKApps = 2;

    public function __construct()
    {
        $this->configArray = $this->getAllConfig();
        var_dump($this->configArray);
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
     * возвращает настройки для подключения к БД
     * @param type $settingValue значение для которого требуется получить настройки 
     * @return type массив с данными из заданной секции настроек
     */
    // TODO: для работы с конфигом надо создать отдельный класс, в котором будет
    // прописано какому классу какой конфиг отдавать
    public function getConfig($settingValue)
    {

        switch ($settingValue) {
            case self::STK:

                $this->ensure(isset($this->configArray['host6597']), "Настройки для константы STK не найдены");

                return $this->configArray['host6597'];
                break;

            case self::STKApps:

                $this->ensure(isset($this->configArray['host6597_test']), "Настройки для константы STKApps не найдены");

                return $this->configArray['host6597_test'];
                break;

            default:
                $this->ensure(false, "Переданная константа для получения настроек не задана");
                break;
        }
    }

    // получает массив с содержимым файла конфигурации
    private function getAllConfig()
    {
        $this->ensure(file_exists(self::CONFIG_FILE_PATH), "Файл с настройками не найден");

        $config_array = parse_ini_file(self::CONFIG_FILE_PATH, true);

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

$mysqlConfig = lConfig::getInstance();
$config_data = $mysqlConfig->getConfig(Config::STKApps);
var_dump($config_data);
