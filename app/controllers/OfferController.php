<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\QuotesModel;

class OfferController extends Controller
{
    private $quotes;
    
    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'][0] = 'offer';
    }
    
    public function getPage()
    {
        $this->view->generate('/random/offerQuote.php', 'indexTemplate.php', $this->data, $this->error);
    }
}

