<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;
use Application\Models\ConfigModel;
use Application\Core\Request;

class OfferModel extends Model
{

    //private $quotesArray = array();
    //private $authorsArray = array();
    private $dbh;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->dbh = new MysqlModel(ConfigModel::UNMARRIED);
        
    }
    
    public function addOffer()
    {
        $offerForm = array();
        
        $offerForm['quoteText'] = $this->request->getProperty('quoteText');
        $offerForm['authorQuote'] = $this->request->getProperty('authorQuote');
        $offerForm['sourceQuote'] = $this->request->getProperty('sourceQuote');
        $offerForm['quoteText'] = $this->request->getProperty('quoteText');
        $offerForm['authorOffer'] = $this->request->getProperty('authorOffer');
        
        if (empty($offerForm['quoteText'])) {
            $this->errors[] = "Текст цитаты не введен";
            return false;
        }
        
        if (iconv_strlen ($offerForm['quoteText']) > 15000) {
            $this->errors[] = "Текст длиннее 15 000 символов";
            return false;
        }
        
        // защита от XSS уязвимостей
        // имеет смысл только в Представлении
        /*
        foreach ($offerForm as $key => $value) {
            $offerForm[$key] = htmlspecialchars($value, ENT_QUOTES);
        }
         * 
         */
        
        $this->dbh->query("INSERT INTO `offer_quotes` (`quote_text`, `author_quote`, `author_offer`, `source_quote`, `comment`) VALUES (?, ?, ?, ?, ?)", 
                'none', '', array($offerForm['quoteText'], $offerForm['authorQuote'], $offerForm['sourceQuote'], $offerForm['quoteText'], $offerForm['authorOffer']));
        
        return true;
    }
}