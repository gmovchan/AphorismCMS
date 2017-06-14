<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Core\Mysql;
use Application\Core\Config;
use Application\Core\ErrorHandler;
use Application\Core\Notificator;

class OfferModel extends Model
{

    private $notificator;

    public function __construct()
    {
        $this->dbh = new Mysql(Config::DB);
        $this->notificator = new Notificator;
    }

    public function addOffer($formContent)
    {
        if (empty($formContent['quoteText'])) {
            $this->errors[] = "Текст цитаты не введен";
            return false;
        }

        // в mysql для поля типа TEXT нельзя указать максимальную длину
        if (iconv_strlen($formContent['quoteText']) > 15000) {
            $this->errors[] = "Текст цитаты длиннее 15 000 символов";
            return false;
        }

        $result = $this->dbh->query("INSERT INTO `offer_quotes` (`quote_text`, `author_quote`, `author_offer`, `source_quote`, `comment`) VALUES (?, ?, ?, ?, ?)", 'rowCount', '', array($formContent['quoteText'], $formContent['authorQuote'], $formContent['authorQuote'], $formContent['sourceQuote'], $formContent['comment']));

        if ($result === 1) {
            $offerId = $this->dbh->query('', 'lastInsertId');
            $this->notificator->sendMailNotification('offer', $offerId);
            $this->successful[] = "Цитата успешно отправлена";
            $this->successful[] = "После проверки администраторам она появится в общем списке";
            return true;
        } else {
            $this->errors[] = "Не удалось отправить предложение из-за ошибки на сервере.";
            return false;
        }  
    }

    public function getOffersAll()
    {
        $offers = $this->dbh->query("SELECT * FROM `offer_quotes` ORDER BY `id` DESC;", 'fetchAll', '');
        return $offers;
    }

    public function delOffer($id)
    {
        ErrorHandler::ensure(!is_null($id), "Не удалось получить id удаляемой цитаты");
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
        ErrorHandler::ensure($this->checkID($formContent['idInDB'], 'offer_quotes'), "Предложение id{$formContent['idInDB']} не найдена в БД");

        if (!$this->checkDataForm($formContent['quoteText'])) {
            return false;
        }

        $rowCount = $this->dbh->query("UPDATE `offer_quotes` SET `quote_text` = ?, `author_quote` = ?, `author_offer` = ?, `source_quote` = ?, `comment` = ?, `author_id` = ?"
                . "  WHERE `id` = ?;", 'rowCount', '', array($formContent['quoteText'], $formContent['authorQuote'], $formContent['creatorQuote'], $formContent['sourceQuote'], $formContent['comment'], $formContent['authorQuoteID'], $formContent['idInDB']));

        if ($rowCount === 1) {
            $this->successful[] = "Изменения в предложении id{$formContent['idInDB']} сохранены";
            return true;
        } else {
            $this->errors[] = "Не удалось сохранить изменения id{$formContent['idInDB']}. Возможно, не было правок.";
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
    // TODO: метод должен проверять все поля формы. Пока он возвращает заглушку 
    // с ошибкой 503, если длина больше чем установлена в БД. Пользователю не очевидно где его ошибка.
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

}
