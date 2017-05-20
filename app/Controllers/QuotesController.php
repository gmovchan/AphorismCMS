<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\QuotesModel;
use Application\Core\Errors;

class QuotesController extends Controller
{
    private $quotes;
    
    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'quotes';
        $this->quotes = new QuotesModel($this->request);
    }
    
    public function getPage()
    {
        $authorID = null;
        $getArray = $this->request->getProperty('GET');
        if (isset($getArray['author_id'])) {
            $authorID = $getArray['author_id'];
        }
        
        $quotes = $this->quotes->getAllQuotes($authorID);

        // если не удалось получить цитаты, то вернет страницу 404
        if ($quotes) {
            $this->data['quotes'] = $quotes;
            $this->view->generate('/index/quotes.php', 'indexTemplate.php', $this->data);
        } else {
            Errors::getErrorPage404();
        }
    }
    
}

