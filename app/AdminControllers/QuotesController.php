<?php

namespace Application\AdminControllers;

use Application\Core\Controller;
use Application\Models\QuotesModel;
use Application\Core\Errors;
use Application\AdminControllers\AdminController;

class QuotesController extends AdminController
{
    private $quotes;
    
    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'quotes';
        $this->quotes = new QuotesModel();
        
    }
    
    public function getPage()
    {
        $this->getQuotes();
    }

    public function getQuotes($errors = null, $successful = null)
    {
        if (!is_null($errors)) {
            $this->data['errors'] = $errors;
        }
        
        if (!is_null($successful)) {
            $this->data['successful'] = $successful;
        }
        
        $authorID = null;
        $getArray = $this->request->getProperty('GET');
        
        if (isset($getArray['author_id'])) {
            $authorID = $getArray['author_id'];
        }

        $quotes = $this->quotes->getAllQuotes($authorID);

        // если не удалось получить цитаты, то вернет страницу 404
        if ($quotes) {
            $this->data['quotes'] = $quotes;
            $this->view->generate('/admin/quotes.php', 'adminTemplate.php', $this->data);
        } else {
            Errors::printErrorPage404();
        }
    }
    
}

