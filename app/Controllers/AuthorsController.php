<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\AuthorsModel;
use Application\Models\QuotesModel;
use Application\Core\Errors;

class AuthorsController extends Controller
{

    private $authors;
    private $quotes;

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'authors';
        $this->authors = new AuthorsModel($this->request);
        $this->quotes = new QuotesModel($this->request);
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
