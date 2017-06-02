<?php

namespace Application;

// выводит ошибки PHP в браузере, пригодится на VDS сервере для отладки
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

use Application\Core\FrontController;
use Application\Core\ErrorHandler;
use Application\Core\Config;
use Application\Core\AppException;

ErrorHandler::handleErrors(ErrorHandler::getConfigElement('app_in_production'));

// умышленная ошибка - вызывает исключение
// strpos();

$frontController = new FrontController();
$frontController->start();
