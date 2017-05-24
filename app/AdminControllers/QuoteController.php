<?php

namespace Application\AdminControllers;

use Application\Core\Controller;
use Application\Models\QuotesModel;
use Application\Models\CommentsModel;
use Application\Models\AuthorsModel;
use Application\Core\Errors;
use Application\AdminControllers\QuotesController;

class QuoteController extends AdminController
{

    private $quotes;
    private $comments;
    private $authors;

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'quote';
        $this->quotes = new QuotesModel();
        $this->comments = new CommentsModel();
        $this->authors = new AuthorsModel();
    }

    public function getPage()
    {
        Errors::getErrorPage404();
    }

    public function getQuotes($errors, $successful)
    {
        $quotesController = new QuotesController;
        // открывает в админке список со всеми цитатами
        $quotesController->getQuotes($errors, $successful);
    }

    public function comments()
    {
        // нет пункта меню для этой страницы
        $this->data['thisPage'] = null;

        if (isset($_POST['comment'])) {

            $formContent = $this->request->getProperty('POST');
            $quoteID = $formContent['idInDB'];

            if ($this->comments->addComment($formContent)) {
                $this->data['successful'] = $this->comments->getSuccessful();
                $this->getQuotePage($quoteID);
            } else {
                $this->data['errors'] = $this->comments->getErrors();
                $this->getQuotePage($quoteID);
            }
        } else {
            $getArray = $this->request->getProperty('GET');
            $quoteID = $getArray['quote_id'];
            $this->getQuotePage($quoteID);
        }
    }

    private function getQuotePage($quoteID)
    {
        if (!is_null($quoteID)) {
            $quote = $this->quotes->getQuote($quoteID);
            $comments = $this->comments->getComments($quoteID);

            // если не удалось получить цитату, то вернет страницу 404
            if ($quote) {
                $this->data['quote'] = $quote;
                $this->data['comments'] = $comments;
                $this->view->generate('/admin/quote.php', 'adminTemplate.php', $this->data);
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
            $this->getQuotes(null, $this->quotes->getSuccessful());
        } else {
            $this->getQuotes($this->quotes->getErrors(), null);
        }
    }

    public function editQuote($id)
    {
        // нет пункта меню для этой страницы
        $this->data['thisPage'] = null;

        if (is_null($id)) {
            $getArray = $this->request->getProperty('GET');
            $id = $getArray['quote_id'];
        }

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
            $this->getQuotes(null, $this->quotes->getSuccessful());
        } else {
            $this->data['errors'] = $this->quotes->getErrors();
            $this->editQuote($formContent['idInDB']);
        }
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

}
