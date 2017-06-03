<?php

namespace Application\Core;

use Application\Models\AuthModel;
use Application\Core\Request;
use Application\Core\Config;

class Controller
{

    protected $error = null;
    protected $data = null;
    protected $auth;
    protected $view;
    protected $request;


    function __construct()
    {
        $config = Config::getInstance();
        //$this->config_data = $mysqlConfig->getConfig($settingValue);
        
        $this->view = new View;
        $this->auth = new AuthModel();
        // задает путь к директории /public/, чтобы скрипт было легко модифцировать
        // для выделенного или виртуального хостинга
        $this->data['publicDir'] = $config->getConfigElement(Config::CONSTANTS, 'public_dir');
        // заголовок страницы
        $this->data['title'] = $config->getConfigElement(Config::CONSTANTS, 'title');
        $this->request = new Request;
        $this->data['thisPage'] = null;
    }

    // Проверка авторизации
    protected function checkAuth()
    {
        if (!$this->auth->authorization()) {
            //~ совершаем процедуру выхода
            $this->auth->exit_user();
        }
    }

}
