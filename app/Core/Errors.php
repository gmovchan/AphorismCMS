<?php

namespace Application\Core;

// TODO: перенести сюда метод ensure из Model, потому что он может понадобится и в других классах
class Errors
{

    static public function getErrorPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        /* 
         * Нельзя отдавать заголовок Location при коде 4xx. Он используется с кодом 
         * 3xx в тех случаях, когда страница переехала на другой адрес. 
         * Если страница не найдена, надо отдавать код 404. 
         */
        // header('Location:' . $host . '404');
        exit;
    }

}
