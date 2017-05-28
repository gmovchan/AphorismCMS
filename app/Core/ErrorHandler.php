<?php

namespace Application\Core;

use Application\Core\AppException;

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
        // TODO: добавить функцию записи в лог
        exit;
    }

    // централизованная проверка условия и вызов исключения
    static public function ensure($expr, $message)
    {
        if (!$expr) {
            throw new AppException($message);
        }
    }

    static public function handleErrors($status)
    {
        // 0 - проект на тестовом сервере, 1 - проект в поле
        $appStatus = $status;

        // если нет явной иформации, что проект запущен в поле, то ошибки, в любом
        // случае, будут скрыты за заглушкой
        if ($appStatus !== 0) {

            set_exception_handler(function ($exception) {
                // TODO: добавить функцию записи в лог
                self::printErrorPage503();
            });

            /*
             * Общий обработчик ошибок (он вызывается при любой ошибке PHP, например обращении 
             * к несуществующей переменной или невозможности чтения файла), и в нем выкидывать исключение.
             * Таким образом, любая ошибка или предупреждение приведут к выбросу исключения.
             * Все это делается в несколько строчек с помощью встроенного в PHP класса ErrorException
             */
            set_error_handler(function ($errno, $errstr, $errfile, $errline ) {
                // Не выбрасываем исключение если ошибка подавлена с помощью оператора @
                if (!error_reporting()) {
                    return;
                }

                throw new AppException($errstr, $errno, 0, $errfile, $errline);
            });
        }
    }

    // получат из файла app.ini информацию запущен ли проект в поле
    static public function getAppStatus()
    {
        if (class_exists('Application\Core\Config')) {
            $config = Config::getInstance();
            $constants = $config->getConfig(Config::CONSTANTS);

            if (isset($constants['app_in_production'])) {
                return $constants['app_in_production'];
            } else {
                // TODO: добавить функцию записи в лог
                return null;
            }
        } else {
            // TODO: добавить функцию записи в лог
            return null;
        }
    }

}
