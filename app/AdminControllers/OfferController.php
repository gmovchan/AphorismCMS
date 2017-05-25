<?php

namespace Application\AdminControllers;

use Application\Core\Controller;
use Application\Models\OfferModel;
use Application\Models\QuotesModel;
use Application\Models\AuthorsModel;
use Application\Core\Errors;

class OfferController extends AdminController
{    
    private $offer;
    private $authors;
    private $quotes;
    
    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'offer';
        $this->offer = new OfferModel();
        $this->authors = new AuthorsModel();
        $this->quotes = new QuotesModel();
    }
    
    public function getPage()
    {
        $this->getOffers();
    }
    
    /*
     * Требуется, когда в одном методе контроллера необходимо собрать сообщения 
     * из двух и более моделей.
     */
    private function addSuccessful($messages, $type)
    {
        if (empty($messages)) {
            return;
        }

        switch ($type) {
            case 'successful':
                foreach ($messages as $message) {
                    $this->data['successful'][] = $message;
                }

                break;

            case 'errors':
                foreach ($messages as $message) {
                    $this->data['errors'][] = $message;
                }

                break;

            default:
                break;
        }
    }
    
    public function approveOffer()
    {
        $formContent = $this->request->getProperty('POST');
        $approveOffer = $this->quotes->addQuote($formContent);
        $offerID = $formContent['idInDB'];
        $successful = array();

        // проверка, получилось ли создать цитату
        if ($approveOffer) {
            $this->addSuccessful($this->quotes->getSuccessful(), 'successful');

            // если цитата создана, то предложение удаляется
            if ($this->offer->delOffer($offerID)) {
                $this->addSuccessful($this->offer->getSuccessful(), 'successful');
            } else {
                $this->addSuccessful($this->offer->getErrors(), 'errors');
            }

            $this->getPage();
        } else {
            //$this->data['errors'] = $this->quotes->getErrors();
            $this->addSuccessful($this->quotes->getErrors(), 'errors');

            // создает id в массиве и передает Request, потому что вызываемый метод
            // так его получает
            $getArray = array('offer_id' => $offerID);
            $this->request->setProperty('GET', $getArray);
            $this->editOffer();
        }
    }
    
    public function delOffer()
    {
        $getArray = $this->request->getProperty('GET');
        $id = $getArray['offer_id'];

        if ($this->offer->delOffer($id)) {
            $this->data['successful'] = $this->offer->getSuccessful();
            $this->getOffers();
        } else {
            $this->data['errors'] = $this->offer->getErrors();
            $this->getOffers();
        }
    }
    
    public function editOffer($id = null)
    {
        // нет пункта меню для этой страницы
        $this->data['thisPage'] = null;

        if (is_null($id)) {
            $getArray = $this->request->getProperty('GET');
            $id = $getArray['offer_id'];
        }

        if (!is_null($id)) {
            $offer = $this->offer->getOffer($id);

            // если не удалось получить цитату, то вернет страницу 404
            if (!is_null($offer)) {
                $this->data['authors'] = $this->authors->getAllAuthors('quotes');
                $this->data['offer'] = $offer;
                $this->view->generate('/admin/offerEdit.php', 'adminTemplate.php', $this->data);
            } else {
                Errors::getErrorPage404();
            }
        } else {
            Errors::getErrorPage404();
        }
    }
    
    public function saveOffer()
    {
        $formContent = $this->request->getProperty('POST');
        $resultEdit = $this->offer->saveOffer($formContent);

        if ($resultEdit) {
            $this->data['successful'] = $this->offer->getSuccessful();
            $this->getOffers();
        } else {
            $this->data['errors'] = $this->offer->getErrors();
            $this->editOffer($formContent['idInDB']);
        }
    }
    
    public function getOffers()
    {
        $this->data['thisPage'] = 'offersAdmin';
        $this->data['offers'] = $this->offer->getOffersAll();
        $this->view->generate('/admin/offers.php', 'adminTemplate.php', $this->data);
    }
    
}

