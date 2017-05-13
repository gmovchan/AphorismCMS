<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\QuotesModel;

class RandomController extends Controller
{
    private $quotes;
    
    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'random';
        $this->quotes = new QuotesModel($this->request);
    }
    
    public function getPage()
    {
        $this->data['quote'] = $this->quotes->getQuote();
        $this->view->generate('/index/quoteRandom.php', 'indexTemplate.php', $this->data);
    }
}

