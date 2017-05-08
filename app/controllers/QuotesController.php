<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\QuotesModel;

class QuotesController extends Controller
{
    private $quotes;
    
    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'][0] = 'quotes';
        $this->quotes = new QuotesModel($this->request);
    }
    
    public function getPage()
    {
        $this->data['quotes'] = $this->quotes->getAllQuotes();
        $this->view->generate('/index/quotesAll.php', 'indexTemplate.php', $this->data, $this->error);
    }
}

