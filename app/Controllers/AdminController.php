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

    private $admin;
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
        $this->admin = new AdminModel($this->request);
        $this->quotes = new QuotesModel($this->request);
        $this->offer = new OfferModel($this->request);
        $this->authors = new AuthorsModel($this->request);
    }

    public function getPage()
    {
        $this->data['quotes'] = $this->quotes->getAllQuotes();
        $this->view->generate('/quotesAdmin/quotesAll.php', 'adminTemplate.php', $this->data);
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
        $this->view->generate('/authorsAdmin/authorsAll.php', 'adminTemplate.php', $this->data);
    }

    public function addQuote()
    {
        $this->data['thisPage'] = 'addQuote';
        $this->data['login'] = $this->auth->getLogin();
        $this->data['authors'] = $this->authors->getAllAuthors('names');

        if (isset($_POST['quoteText'])) {

            if ($this->quotes->addQuote()) {
                $this->data['successful'] = $this->quotes->getSuccessful();
                $this->view->generate('/quotesAdmin/quoteAdd.php', 'adminTemplate.php', $this->data);
            } else {
                $this->data['errors'] = $this->quotes->getErrors();
                $this->view->generate('/quotesAdmin/quoteAdd.php', 'adminTemplate.php', $this->data);
            }
        } else {
            $this->view->generate('/quotesAdmin/quoteAdd.php', 'adminTemplate.php', $this->data);
        }
    }

    public function authorAdd()
    {
        if ($this->authors->authorAdd()) {
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
        $this->view->generate('/offerAdmin/offersAll.php', 'adminTemplate.php', $this->data);
    }

    public function delQuote()
    {
        if ($this->quotes->delQuote()) {
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
        
        if (isset($_POST['quote_id'])) {
            $quoteID = $_POST['quote_id'];
        } else {
            $quoteID = $this->request->getProperty('quote_id');
        }

        if (!is_null($quoteID)) {
            $quote = $this->quotes->getQuote($quoteID);

            // если не удалось получить цитату, то вернет страницу 404
            if ($quote) {
                $this->data['authors'] = $this->authors->getAllAuthors('quotes');
                $this->data['quote'] = $quote;
                $this->view->generate('/quotesAdmin/quoteEdit.php', 'adminTemplate.php', $this->data);
            } else {
                Errors::getErrorPage404();
            }
        } else {
            Errors::getErrorPage404();
        }
    }

    public function quoteSaveChanges()
    {
        $resultEdit = $this->quotes->quoteEditSave();

        if ($resultEdit) {
            $this->data['successful'] = $this->quotes->getSuccessful();
            $this->getPage();
        } else {
            $this->data['errors'] = $this->quotes->getErrors();
            $this->quoteEdit();
        }
    }

}
