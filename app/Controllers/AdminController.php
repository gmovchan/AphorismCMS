<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\AdminModel;
use Application\Models\QuotesModel;
use Application\Models\OfferModel;

class AdminController extends Controller
{    
    private $admin;
    private $quotes;
    private $offer;
    
    public function __construct()
    {
        parent::__construct();
        
        // авторизация, если не пройдена, то произойдет переход к форме аутентификации
        $this->checkAuth();
        
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'quotesAdmin';
        
        // FIXME: создавать кучу объектов, которые могут не пригодится - плохая идея
        $this->admin = new AdminModel($this->request);
        $this->quotes = new QuotesModel($this->request);
        $this->offer = new OfferModel($this->request);
    }
    
    public function getPage()
    {
        $this->data['quotes'] = $this->quotes->getAllQuotes();
        $this->view->generate('/quotesAdmin/quotesAll.php', 'adminTemplate.php', $this->data, $this->error);
    }
    
    public function quotes()
    {
        $this->getPage();
    }

        public function addQuote()
    {
        $this->data['login'] = $this->auth->getLogin();
        $this->data['thisPage'][0] = 'quoteAdd';
        $this->view->generate('/quotesAdmin/quoteAdd.php', 'adminTemplate.php', $this->data, $this->error);
    }
}

