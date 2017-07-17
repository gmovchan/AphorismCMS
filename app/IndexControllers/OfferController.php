<?php

namespace Application\IndexControllers;

use Application\Core\Controller;
use Application\Models\OfferModel;

class OfferController extends Controller
{    
    private $offer;
    
    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'offer';
        $this->data['title'] = "Добавить";
        $this->offer = new OfferModel();
    }
    
    public function getPage()
    {
        $this->view->generate('/index/offerQuote.php', 'indexTemplate.php', $this->data);
    }
    
    public function addOffer()
    {
        $formContent = $this->request->getProperty('POST');
        
        if ($this->offer->addOffer($formContent)) { 
            $this->data['successful'] = $this->offer->getSuccessful();
            $this->view->generate('/index/offerQuote.php', 'indexTemplate.php', $this->data);
        } else {
            $this->data['errors'] = $this->offer->getErrors();
            $this->view->generate('/index/offerQuote.php', 'indexTemplate.php', $this->data);
        }
    }
}

