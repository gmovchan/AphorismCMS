<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\AdminModel;
use Application\Models\QuotesModel;
use Application\Models\OfferModel;
use Application\Models\AuthorsModel;
use Application\Core\Errors;

class AdminController extends Controller
{

    private $quotes;
    private $offer;
    private $authors;

    public function __construct()
    {
        parent::__construct();

        // авторизация, если не пройдена, то произойдет переход к форме аутентификации
        $this->checkAuth();

        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'quotesAdmin';

        /*
         * FIXME: создавать кучу объектов, которые могут не пригодится - плохая 
         * идея. Ошибка в одном сломает весь скрипт. Но пока не придумал ничего лучше
         */
        $this->quotes = new QuotesModel();
        $this->offer = new OfferModel();
        $this->authors = new AuthorsModel();
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
            $this->view->generate('/admin/quotes.php', 'adminTemplate.php', $this->data);
        } else {
            Errors::getErrorPage404();
        }
    }

    public function quotes()
    {
        $this->getPage();
    }

    public function authors()
    {
        $this->data['thisPage'] = 'authorsAdmin';
        // 'quotes' - сортировка по количеству цитат;
        $this->data['authors'] = $this->authors->getAllAuthors('quotes');
        $this->view->generate('/admin/authors.php', 'adminTemplate.php', $this->data);
    }

    public function addQuote()
    {
        $formContent = $this->request->getProperty('POST');
        $this->data['thisPage'] = 'addQuote';
        $this->data['login'] = $this->auth->getLogin();
        $this->data['authors'] = $this->authors->getAllAuthors('names');

        if (isset($_POST['quoteText'])) {

            if ($this->quotes->addQuote($formContent)) {
                $this->data['successful'] = $this->quotes->getSuccessful();
                $this->view->generate('/admin/quoteAdd.php', 'adminTemplate.php', $this->data);
            } else {
                $this->data['errors'] = $this->quotes->getErrors();
                $this->view->generate('/admin/quoteAdd.php', 'adminTemplate.php', $this->data);
            }
        } else {
            $this->view->generate('/admin/quoteAdd.php', 'adminTemplate.php', $this->data);
        }
    }

    public function addAuthor()
    {
        $formContent = $this->request->getProperty('POST');
        $name = $formContent['authorName'];
        if ($this->authors->addAuthor($name)) {
            $this->data['successful'] = $this->authors->getSuccessful();
            $this->authors();
        } else {
            $this->data['errors'] = $this->authors->getErrors();
            $this->authors();
        }
    }

    public function offers()
    {
        $this->data['thisPage'] = 'offersAdmin';
        $this->data['offers'] = $this->offer->getOffersAll();
        $this->view->generate('/admin/offers.php', 'adminTemplate.php', $this->data);
    }

    public function editOffer()
    {
        // нет пункта меню для этой страницы
        $this->data['thisPage'] = null;

        $getArray = $this->request->getProperty('GET');
        $id = $getArray['offer_id'];

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

    public function delQuote()
    {
        $getArray = $this->request->getProperty('GET');
        $id = $getArray['quote_id'];

        if ($this->quotes->delQuote($id)) {
            $this->data['successful'] = $this->quotes->getSuccessful();
            $this->getPage();
        } else {
            $this->data['errors'] = $this->quotes->getErrors();
            $this->getPage();
        }
    }

    public function quoteEdit()
    {
        // нет пункта меню для этой страницы
        $this->data['thisPage'] = null;

        $getArray = $this->request->getProperty('GET');
        $id = $getArray['quote_id'];

        if (!is_null($id)) {
            $quote = $this->quotes->getQuote($id);

            // если не удалось получить цитату, то вернет страницу 404
            if ($quote) {
                $this->data['authors'] = $this->authors->getAllAuthors('quotes');
                $this->data['quote'] = $quote;
                $this->view->generate('/admin/quoteEdit.php', 'adminTemplate.php', $this->data);
            } else {
                Errors::getErrorPage404();
            }
        } else {
            Errors::getErrorPage404();
        }
    }

    public function saveQuote()
    {
        $formContent = $this->request->getProperty('POST');
        $resultEdit = $this->quotes->saveQuote($formContent);

        if ($resultEdit) {
            $this->data['successful'] = $this->quotes->getSuccessful();
            $this->getPage();
        } else {
            $this->data['errors'] = $this->quotes->getErrors();
            $this->quoteEdit();
        }
    }

    public function delOffer()
    {
        $getArray = $this->request->getProperty('GET');
        $id = $getArray['offer_id'];

        if ($this->offer->delOffer($id)) {
            $this->data['successful'] = $this->offer->getSuccessful();
            $this->offers();
        } else {
            $this->data['errors'] = $this->offer->getErrors();
            $this->offers();
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

    /*
     * требуется, когда в одном методе контроллера требуется собрать сообщения 
     * из двух и более моделей
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
    
    public function saveOffer()
    {
        $formContent = $this->request->getProperty('POST');
        $resultEdit = $this->offer->saveOffer($formContent);

        if ($resultEdit) {
            $this->data['successful'] = $this->offer->getSuccessful();
            $this->offers();
        } else {
            $this->data['errors'] = $this->offer->getErrors();
            $this->offerEdit();
        }
    }

}
