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

    public function delOffer($id)
    {
        $this->ensure(!is_null($id), "Не удалось получить id удаляемой цитаты");
        $delete = $this->dbh->query("DELETE FROM `offer_quotes` WHERE `id` = ?;", 'rowCount', '', array($id));
        if ($delete === 1) {
            $this->successful[] = "Предожение id{$id} удалено.";
            return true;
        } else {
            $this->errors[] = "Не удалось удалить предложение id{$id}";
            return false;
        }
    }

    public function saveOffer($formContent)
    {
        $this->ensure($this->checkID($formContent['idInDB']), "Цитата id{$formContent['idInDB']} не найдена в БД");

        if (!$this->checkDataForm($formContent['quoteText'])) {
            return false;
        }
        
        $rowCount = $this->dbh->query("UPDATE `offer_quotes` SET `quote_text` = ?, `author_quote` = ?, `author_offer` = ?, `source_quote` = ?, `comment` = ?, `author_id` = ?"
                . "  WHERE `id` = ?;", 'rowCount', '', array($formContent['quoteText'], $formContent['authorQuote'], $formContent['creatorQuote'], $formContent['sourceQuote'], $formContent['comment'], $formContent['authorQuoteID'], $formContent['idInDB']));

        if ($rowCount === 1) {
            $this->successful[] = "Изменения в цитате id{$formContent['idInDB']} сохранены";
            return true;
        } else {
            $this->errors[] = "Не удалось сохранить изменения id{$formContent['idInDB']}";
            return false;
        }

    }

    public function getOffer($id)
    {
        $offer = $this->dbh->query("SELECT * FROM `offer_quotes` WHERE id = ?;", 'fetch', '', array($id));
        if ($offer) {
            return $offer;
        } else {
            return null;
        }
    }

    // проверяет поля формы на валидность
    // FIXME: дублируется метод, такой же есть в QuotesModel
    private function checkDataForm($quoteText)
    {
        if (empty($quoteText)) {
            $this->errors[] = "Текст цитаты не введен";
            return false;
        }

        if (iconv_strlen($quoteText) > 15000) {
            $this->errors[] = "Текст длиннее 15 000 символов";
            return false;
        }

        return true;
    }
    
    // проверяет существование цитаты
    // FIXME: дублируется метод, такой же есть в QuotesModel
    private function checkID($id)
    {
        $query = $this->dbh->query("SELECT * FROM `quotes` WHERE `id` = ?;", 'rowCount', '', array($id));
        return $query === 1;
    }

}
