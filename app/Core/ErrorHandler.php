<?php

namespace Application\Core;

use Application\Core\AppException;
use Application\Core\Notificator;

class ErrorHandler
{

    static public function printErrorPage404()
    {
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        require_once __DIR__ . '/../../views/errors/error404.php';
        exit;
    }

    static public function printErrorPage503()
    {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        header('Retry-After: 300');
        require_once __DIR__ . '/../../views/errors/error503.php';
        exit;
    }

    // централизованная обработка исключений выброшенных в ходе проверки условия
    static public function ensure($expr, $message)
    {
        if (!$expr) {
            throw new AppException($message);
        }
    }

    /// FIXME: хорошо подумать как лучше переписать эту функцию
    static public function handleErrors($status)
    {
        // 0 - проект на тестовом сервере, 1 - проект на общедоступном сервере
        $appStatus = $status;

        // если нет явной иформации, что проект запущен на общедоступном сервере, то ошибки в любом
        // случае будут скрыты за заглушкой
        if ($appStatus !== 0) {
            set_exception_handler(function ($e) {
                // записывает ошибку в лог
                self::addToLog($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
                // открывает заглушку
                self::printErrorPage503();
            });
        }

        /*
         * Общий обработчик ошибок (он вызывается при любой ошибке PHP, например обращении 
         * к несуществующей переменной или невозможности чтения файла), и в нем выкидывать исключение.
         * Таким образом, любая ошибка или предупреждение приведут к выбросу исключения.
         * Все это делается в несколько строчек с помощью встроенного в PHP класса ErrorException
         */
        // FIXME: возможно, дублирует set_exception_handler или наоборот
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {

            // Не выбрасываем исключение если ошибка подавлена с помощью оператора @
            if (!error_reporting()) {
                return;
            }

            self::addToLog($errno, $errstr, $errfile, $errline);
            throw new AppException($errstr, $errno, 0, $errfile, $errline);
        });

        self::ensure(!is_null($appStatus), "Не удалось получить настройку режима работы приложения.");
    }

    /**
     * Получает из файла app.ini информацию запущен ли проект на общедоступном сервере
     * @param type $arrayName имя секции файла конфигурации, получается через 
     * константу, например 'Config::CONSTANTS'
     * @param string $elemName имя элемента из секции файла кофигурации
     * @return type
     */
    static public function getConfigElement($elemName)
    {

        // проверка нужна чтобы скрыть ошибки до того как будет объявлен обработчик исключений по умолчанию
        if (class_exists('Application\Core\Config')) {
            $config = Config::getInstance();
            return $config->getConfigElement(Config::CONSTANTS, $elemName);
        } else {
            return null;
        }
    }

    // FIXME: Если произошла ошибка при добавлении в лог и если включена заглушка 
    // для ошибок, например не получилось создать файл или записать в него строку, 
    // то об этом никто не узнает
    // TODO: Добавить функцию отправки информацию о фатальных ошибках администратору на почту
    static public function addToLog($errno, $errstr, $errfile, $errline)
    {
        $logFileName = __DIR__ . '/../../' . self::getConfigElement('errors_log_file_name');
        //максимальный размер лог файла в килобайтах
        $logFileMaxSize = self::getConfigElement('errors_log_file_max_size') * 1024;

        $timestamp = date("Y-m-d H:i:s");
        $separator = ' || ';
        $errorString = $timestamp . $separator . $errno . $separator . $errstr .
                $separator . $errfile . $separator . $errline . PHP_EOL;

        if (self::checkLogFile($logFileName, $logFileMaxSize)) {
            error_log($errorString, 3, $logFileName);
        }

        self::sendMailToAdmin($errorString);
    }

    static private function checkLogFile($logFileName, $logFileMaxSize)
    {
        if (file_exists($logFileName)) {

            // если превышен размер файла, то он будет пересоздан
            if (filesize($logFileName) >= $logFileMaxSize) {
                unlink($logFileName);
                // пытается создать файл и если не получится, то вернёт исключение
                self::createLogFile($logFileName);
            }
        } else {
            self::createLogFile($logFileName);
        }

        self::ensure(is_writable($logFileName), 'Лог-файл не доступен для записи.');

        return true;
    }

    // создает лог-файл или сообщает об ошибке, если не удалось создать
    static private function createLogFile($logFileName)
    {
        self::ensure(touch($logFileName), 'Не удалось создать лог-файл ошибок.');
    }

    // отправляет администратору на почту уведомление об ошибке 
    static private function sendMailToAdmin($message)
    {
        $notificator = new Notificator;
        $notificator->sendMailNotification('exception', '', $message);
        unset($notificator);
    }

}
