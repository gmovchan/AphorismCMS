<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\QuotesModel;
use Application\Models\CommentsModel;
use Application\Core\Errors;

class QuoteController extends Controller
{

    private $quotes;
    private $comments;

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'quote';
        $this->quotes = new QuotesModel();
        $this->comments = new CommentsModel();
    }

    public function getPage()
    {
        $getArray = $this->request->getProperty('GET');
        $quoteID = $getArray['quote_id'];

        if (!is_null($quoteID)) {
            $quote = $this->quotes->getQuote($quoteID);
            // если не удалось получить цитату, то вернет страницу 404
            if ($quote) {
                $this->data['quote'] = $quote;
                $this->view->generate('/index/quoteRandom.php', 'indexTemplate.php', $this->data);
            } else {
                Errors::getErrorPage404();
            }
        } else {
            $this->data['quote'] = $this->quotes->getRandomQuote();
            $this->view->generate('/index/quoteRandom.php', 'indexTemplate.php', $this->data);
        }
    }

    public function comments()
    {

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
            ;
            // если не удалось получить цитату, то вернет страницу 404
            if ($quote) {
                $this->data['quote'] = $quote;
                $this->data['comments'] = $comments;
                $this->view->generate('/index/quote.php', 'indexTemplate.php', $this->data);
            } else {
                Errors::getErrorPage404();
            }
        } else {
            Errors::getErrorPage404();
        }
    }

}
