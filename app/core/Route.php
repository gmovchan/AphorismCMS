<?php

namespace Application\Core;

class Route
{

    public function start()
    {
        $get = null;
        $controllerName = 'Random';
        $actionName = 'getPage';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        if (!empty($routes[1])) {
            
            // если путь содержит GET переменные, то он будет от них очищен, 
            // чтобы не мешать вызову контроллера, класс Reques их все равно получит
            if (strripos($routes[1], '?')) {
                $routes[1] = explode('?', $routes[1]);
                $routes[1] = $routes[1][0];
            }
            
            // делает первую блукву прописной, остальные строчными, потому что 
            // так выглядят названия соответствующих классов и файлов, например AuthModel
            $controllerName = ucfirst(strtolower($routes[1]));
        } else {
            // по умолчанию отправляет на страницу со списком объявлений
            // без изменения адреса - будут ломаться ссылки во вью
            $url = 'Location: /' . strtolower($controllerName) . '/';
            header($url);
        }
        
        

        if (!empty($routes[2])) {
            /*
             * если передаются get переменные, то они будут отделены от названия метода
             * и переданы в качестве аргумента в его вызов
             */
            if (isset($_GET)) {
                $get = $_GET;
                // отделяет имя метода от переменных
                $routes[2] = explode('?', $routes[2]);
                $routes[2] = $routes[2][0];
            }
            $actionName = strtolower($routes[2]);
        }
        
        $controllerlClass = $controllerName . 'Controller';
        $controllerNamespace = 'Application\\Controllers\\' . $controllerlClass;
        if (!class_exists($controllerNamespace)) {
            $this->getErrorPage404();
        }

        $controller = new $controllerNamespace;
        $action = $actionName;

        if (method_exists($controller, $action)) {
            $controller->$action($get);
        } else {
            $this->getErrorPage404();
        }
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
