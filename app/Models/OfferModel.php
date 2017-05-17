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

    public function addOffer($formContent)
    {
        if (empty($formContent['quoteText'])) {
            $this->errors[] = "Текст цитаты не введен";
            return false;
        }
        /*
         * TODO: написать функцию, которая будет возвращать ошибку и её описания, если текст превышает максимальную длину
         * пока скрипт просто выкидывает исключение
         * 
         */

        // потому что в mysql для поля типа TEXT нельзя указать максимальную длину
        if (iconv_strlen($formContent['quoteText']) > 15000) {
            $this->errors[] = "Текст цитаты длиннее 15 000 символов";
            return false;
        }

        $this->dbh->query("INSERT INTO `offer_quotes` (`quote_text`, `author_quote`, `author_offer`, `source_quote`, `comment`) VALUES (?, ?, ?, ?, ?)", 'none', '', array($formContent['quoteText'], $formContent['authorQuote'], $formContent['authorQuote'], $formContent['sourceQuote'], $formContent['comment']));

        $this->successful[] = "Цитата успешно отправлена";
        $this->successful[] = "После проверки администраторам она появится в общем списке";

        return true;
    }

    public function getOffersAll()
    {
        $offers = $this->dbh->query("SELECT * FROM `offer_quotes` ORDER BY `id` DESC;", 'fetchAll', '');
        return $offers;
    }
    
    public function delOffer()
    {
        
    }
    
    public function editOffer()
    {
        
    }
    
    public function approveOffer()
    {
        
    }

}
