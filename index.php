<?php

namespace Application;

require __DIR__ . '/vendor/autoload.php';

use Application\Core\Route;
use Application\Core\ExceptionMy;

try {
    /*
     * Общий обработчик ошибок (он вызывается при любой ошибке PHP, например обращении 
     * к несуществующей переменной или невозможности чтения файла), и в нем выкидывать исключение.
     * Таким образом, любая ошибка или предупреждение приведут к выбросу исключения.
     * Все это делается в несколько строчек с помощью встроенного в PHP класса ErrorException
     */
    set_error_handler(function ($errno, $errstr, $errfile, $errline ) {
        // Не выбрасываем исключение если ошибка подавлена с
        // помощью оператора @
        if (!error_reporting()) {
            return;
        }

        throw new ExceptionMy($errstr, $errno, 0, $errfile, $errline);
    });

    // умышленная ошибка - вызывает исключение
    strpos();

    $route = new Route();
    $route->start();
} catch (ExceptionMy $ex) {
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 300');
    // TODO: добавить функцию записи в лог
    exit;
}