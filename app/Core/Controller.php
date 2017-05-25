<?php

namespace Application\Core;

use Application\Models\AuthModel;
use Application\Core\Request;

class Controller
{

    protected $error = null;
    protected $data = null;
    protected $auth;
    protected $view;
    protected $request;


    function __construct()
    {
        $this->view = new View;
        $this->auth = new AuthModel();
        // задает путь к директории /public/, чтобы скрипт было легко модифцировать
        // для выделенного или виртуального хостинга
        $this->data['publicDir'] = '/public/';
        $this->request = new Request;
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
