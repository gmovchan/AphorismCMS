<?php

namespace Application\IndexControllers;

use Application\Core\Controller;
use Application\Models\QuotesModel;
use Application\Models\CommentsModel;
use Application\Core\ErrorHandler;

class QuoteController extends Controller
{

    private $quotes;
    private $comments;

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'quote';
        $this->data['title'] = "Цитата";
        $this->quotes = new QuotesModel();
        $this->comments = new CommentsModel();
    }

    public function getPage()
    {
        $getArray = $this->request->getProperty('GET');
        
        if (!empty($getArray)) {
            $quoteID = $getArray['quote_id'];
        } else {
            $quoteID = null;
        }

        if (!is_null($quoteID)) {
            $this->data['thisPage'] = null;
            $quote = $this->quotes->getQuote($quoteID);
            // если не удалось получить цитату, то вернет страницу 404
            if ($quote) {
                $this->data['quote'] = $quote;
                $this->view->generate('/index/randomQuote.php', 'indexMiddleTemplate.php', $this->data);
            } else {
                ErrorHandler::printErrorPage404();
            }
        } else {
            $this->data['title'] = "Рандом";
            $this->data['quote'] = $this->quotes->getRandomQuote();
            $this->view->generate('/index/randomQuote.php', 'indexMiddleTemplate.php', $this->data);
        }
    }

    public function comments()
    {
        // нет пункта меню для этой страницы
        $this->data['thisPage'] = null;
        $this->data['title'] = "Комментарии";

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
                $this->view->generate('/index/quote.php', 'indexTemplate.php', $this->data);
            } else {
                ErrorHandler::printErrorPage404();
            }
        } else {
            ErrorHandler::printErrorPage404();
        }
    }

}
