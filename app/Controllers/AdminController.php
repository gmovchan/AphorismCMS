<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\AdminModel;
use Application\Models\QuotesModel;
use Application\Models\OfferModel;
use Application\Models\AuthorsModel;

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

        // FIXME: создавать кучу объектов, которые могут не пригодится - плохая идея
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
        $this->data['thisPage'] = 'quoteAdd';
        $this->data['login'] = $this->auth->getLogin();
        $this->data['authors'] = $this->authors->getAllAuthors('names');

        if (isset($_POST['quoteText'])) {

            if ($this->quotes->addQuote()) {
                $this->data['successful'] = $this->authors->getSuccessful();
                $this->view->generate('/quotesAdmin/quoteAdd.php', 'adminTemplate.php', $this->data);
            } else {
                $this->data['errors'] = $this->authors->getErrors();
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

}
