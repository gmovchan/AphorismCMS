<?php

namespace Application\IndexControllers;

use Application\Core\Controller;
use Application\Models\AuthorsModel;
use Application\Models\QuotesModel;
use Application\Core\ErrorHandler;

class AuthorsController extends Controller
{

    private $authors;
    private $quotes;

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'authors';
        $this->data['title'] = "Авторы";
        $this->authors = new AuthorsModel();
        $this->quotes = new QuotesModel();
    }

    public function getPage()
    {
        $this->authors();
    }

    public function authors()
    {
        // 'quotes' - сортировка по количеству цитат;
        $this->data['authors'] = $this->authors->getAllAuthors('quotes');
        $this->view->generate('/index/authors.php', 'indexTemplate.php', $this->data);
    }

}
