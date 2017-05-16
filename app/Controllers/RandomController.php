<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Models\QuotesModel;
use Application\Core\Errors;

class RandomController extends Controller
{

    private $quotes;

    public function __construct()
    {
        parent::__construct();
        // переменная содержит название загружаемой страницы для выделения пункта меню
        $this->data['thisPage'] = 'random';
        $this->quotes = new QuotesModel($this->request);
    }

    public function getPage()
    {
        $quoteID = $this->request->getProperty('quote_id');

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

}
