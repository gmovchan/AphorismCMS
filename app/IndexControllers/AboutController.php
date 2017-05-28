<?php

namespace Application\IndexControllers;

use Application\Core\Controller;
use Application\Core\ErrorHandler;

class AboutController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'about';
    }

    public function getPage()
    {
                $this->view->generate('/index/about.php', 'indexMiddleTemplate.php', $this->data);
    }

    

}
