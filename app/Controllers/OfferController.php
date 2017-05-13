<?php

namespace Application\Controllers;

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
        $this->offer = new OfferModel($this->request);
    }
    
    public function getPage()
    {
        $this->view->generate('/offer/offerQuote.php', 'indexTemplate.php', $this->data);
    }
    
    public function addOffer()
    {
        
        if ($this->offer->addOffer()) { 
            $this->data['successful'] = $this->offer->getSuccessful();
            $this->view->generate('/offer/successfullAddOffer.php', 'indexTemplate.php', $this->data);
        } else {
            $this->data['errors'] = $this->offer->getErrors();
            $this->view->generate('/offer/offerQuote.php', 'indexTemplate.php', $this->data);
        }
    }
}

