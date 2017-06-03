<?php

namespace Application\Core;

use Application\Core\Model;
use PDO;
use Application\Core\Config;

/**
 * Объект класса подключается к БД и работает с запросами
 * Новый уровень абстракции для работы с БД и запросами необходим, чтобы
 * если изменится способ подключения и настройки, то достаточно было бы изменить
 * только этот класс
 */
class Mysql extends Model
{

    //хранит подключение к БД для доступа к нему из методов класса
    //private $dbh;
    private $config_data;

    /**
     * 
     * @param type $settingValue
     */
    public function __construct($settingValue)
    {
        // получает настройки для соединения с БД
        $mysqlConfig = Config::getInstance();
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
     * Аргумент $num стал не нужен, запрос 'num_row' заменён на 'fetch'
     * но пусть будет, чтобы не переделывать четыре десятка запросов из-за
     * одних пустых кавычек
     */
    public function query($query, $typeQuery = null, $num = null, array $queryParam = array())
    {
        if ($q = $this->dbh->prepare($query)) {
            switch ($typeQuery) {

                /*
                 * возвращает одну строку в виде массива, где ключ - имя столбца 
                 * или порядковый номер колонки
                 */
                case 'fetch':
                    $q->execute($queryParam);
                    return $q->fetch(PDO::FETCH_BOTH);
                    break;

                // получает все строки в виде массива
                case 'fetchAll':
                    $q->execute($queryParam);
                    return $q->fetchAll();
                    break;

                case 'none':
                    $q->execute($queryParam);
                    return $q;
                    break;

                // возвращает количество столбцов, модифицированных запросом
                case 'rowCount':
                    $q->execute($queryParam);
                    return $q->rowCount();
                    break;
                
                // возвращает id последней добавленной в БД строки
                case 'lastInsertId':
                    return intval($this->dbh->lastInsertId());
                    break;
                    
                default:
                    // выкидывает исключение и завершает скрипт, если не найден переданный тип SQL запроса
                    $this->ensure(false, "Ошибка при указании типа SQL запроса");
            }
        }
    }

}
