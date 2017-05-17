<?php

namespace Application\Core;
use Application\Core\Errors;

class Route
{

    public function start()
    {
        $resultParseUrl = $this->parseURL();

        $controllerlClass = $resultParseUrl['controllerName'] . 'Controller';
        $controllerNamespace = 'Application\\Controllers\\' . $controllerlClass;
        if (!class_exists($controllerNamespace)) {
            Errors::getErrorPage404();
        }

        $controller = new $controllerNamespace;
        $action = $resultParseUrl['actionName'];

        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            Errors::getErrorPage404();
        }
    }

    private function parseURL()
    {
        $controllerName = 'Quote';
        $actionName = 'getPage';

        $urlParsed = parse_url($_SERVER['REQUEST_URI']);
        $routes = explode('/', $urlParsed['path']);

        if (!empty($routes[1])) {

            // делает первую блукву прописной, остальные строчными, потому что 
            // так выглядят названия соответствующих классов и файлов, например AuthModel
            $controllerName = ucfirst(strtolower($routes[1]));
        } else {
            // по умолчанию отправляет на страницу по умолчанию
            // без изменения адреса - будут ломаться ссылки во вью
            $this->goToPage($controllerName);
        }

        if (!empty($routes[2])) {

            $actionName = strtolower($routes[2]);
        }

        return array(
            'controllerName' => $controllerName,
            'actionName' => $actionName,
        );
    }
    
    // переходит на страницу переданную страницу
    private function goToPage($defaultPage)
    {
        $url = 'Location: /' . strtolower($defaultPage) . '/';
        header($url);
        // прекращает выполнять функцию
        exit();
    }

    private function getErrorPage404()
    {
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        header('Location:' . $host . '404');
        exit;
    }

}
