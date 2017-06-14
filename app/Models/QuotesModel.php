<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Core\Mysql;
use Application\Core\Config;
use Application\Models\AuthorsModel;
use Application\Models\CommentsModel;
use Application\Core\ErrorHandler;

class QuotesModel extends Model
{

    private $comments;

    public function __construct()
    {
        $this->dbh = new Mysql(Config::DB);
        $this->comments = new CommentsModel();
    }

    public function getAllQuotes($author = null)
    {
        if (is_null($author)) {
            // получает все цитаты
            $quotes = $this->dbh->query("SELECT quotes.quote_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes LEFT JOIN authors ON quotes.author_id=authors.id  ORDER BY quotes.id DESC;", 'fetchAll', '');
        } else {
            // получает только цитаты конкретного автора
            $quotes = $this->dbh->query("SELECT quotes.quote_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON "
                    . "quotes.author_id=authors.id  WHERE authors.id = ? ORDER BY quotes.id DESC;", 'fetchAll', '', array($author));
        }
        
        if (empty($quotes)) {
            ErrorHandler::ensure(false, "В БД нет ни одной цитаты.");
        }
        
        // обрезает длинные цитаты
        $quotes = $this->checkLengthTextQuotes($quotes);
        // получает количество комментариев
        $quotes = $this->getAmountComments($quotes);
        
        return $quotes;
    }
    
    private function getAmountComments($quotes)
    {
        foreach ($quotes as $quoteKey => $quoteValue) {
            $quotes[$quoteKey]['amountComments'] = $this->comments->countComments($quoteValue['quote_id']);
        }
        
        return $quotes;
    }

    // Получает цитату по её id
    public function getQuote($id)
    {
        $quote = $this->dbh->query("SELECT quotes.quote_text AS `text`, quotes.id AS `quote_id`, quotes.source AS `source`, quotes.creator AS `creator`,  authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes LEFT JOIN authors ON quotes.author_id=authors.id WHERE quotes.id = ?;", 'fetch', '', array($id));

        if ($quote) {
            // поместил аргумент в массив, потому что функция работает только с массивами
            $quote = $this->getAmountComments(array($quote));
            // извлекает цитату из возвращенного массива
            $quote = $quote[0];
            
            $prevAndNextRows = $this->getPrevAndNextRows($quote['quote_id']);
            $quote['previous_id'] = $prevAndNextRows['previous_id'];
            $quote['next_id'] = $prevAndNextRows['next_id'];
            $quote['random_id'] = $this->getRandomQuoteID();
            $quote['description'] = mb_substr(trim($quote['text']), 0, 250)."...";
            return $quote;
        } else {
            return null;
        }
    }

    // получает предыдущий и следующий ID, нужны для перехода к предыдущей или следующей цитатам
    private function getPrevAndNextRows($id)
    {
        $idForReturn = array();

        // если просто получать по предыдущему и следующему id, то удалив какую-то позицию - переход может сломаться
        $prevAndNextID = $this->dbh->query("SELECT quotes.id FROM `quotes` WHERE (`id` = (SELECT MAX(`id`) "
                . "FROM `quotes` WHERE `id` < ?) OR `id` = (SELECT MIN(`id`) FROM `quotes` WHERE `id` > ?));", 'fetchAll', '', array($id, $id));

        // проверка на случай если цитата будет первой или последней
        // чтобы правило отобразить кнопки перехода вперед-назад
        if ((int) $id === 1) {
            $idForReturn['previous_id'] = 0;
            $idForReturn['next_id'] = 2;
        } elseif ($id > 1 && count($prevAndNextID) === 1) {
            $idForReturn['previous_id'] = $prevAndNextID[0]['id'];
            $idForReturn['next_id'] = 0;
        } else {
            $idForReturn['previous_id'] = $prevAndNextID[0]['id'];
            $idForReturn['next_id'] = $prevAndNextID[1]['id'];
        }

        return $idForReturn;
    }

    // Получает случайную цитату
    public function getRandomQuote()
    {
        $id = $this->getRandomQuoteID();
        $randomQuote = $this->getQuote($id);
        return $randomQuote;
    }

    // Возвращает ID случайной цитаты
    private function getRandomQuoteID()
    {
        $countRows = $this->dbh->query("SELECT COUNT(*) FROM `quotes`;", 'fetch', '', array());
        $countRows = $countRows[0];

        if ($countRows == 0) {
            ErrorHandler::ensure(false, 'В БД нет ни одной цитаты.');
        }
        
        // отнял 1 из-за смещения при использовании LIMIT
        $randRow = rand(1, $countRows) - 1;
        $id = $this->dbh->query("SELECT `id` FROM `quotes` LIMIT $randRow, 1;", 'fetch', '', array());
        $id = $id['id'];

        if ($id) {
            return (int) $id;
        } else {
            $this->errors[] = "не удалось получить случайный id цитаты.";
            return false;
        }
    }

    // Добавляет новую цитату, должна вызываться только из панели администратора
    public function addQuote($formContent)
    {
        if (!$this->checkDataForm($formContent['quoteText'])) {
            return false;
        }

        $this->dbh->query("INSERT INTO `quotes` (`quote_text`, `author_id`, `source`, `creator`) "
                . "VALUES (?, ?, ?, ?)", 'none', '', array($formContent['quoteText'], $formContent['authorQuoteID'], $formContent['sourceQuote'], $formContent['creatorQuote']));
        $this->successful[] = "Цитата успешно добавлена";
        return true;
    }

    // Удалить цитату из БД
    public function delQuote($id)
    {
        ErrorHandler::ensure(!is_null($id), "Не удалось получить id удаляемой цитаты");
        $delete = $this->dbh->query("DELETE FROM `quotes` WHERE `id` = ?;", 'rowCount', '', array($id));
        if ($delete === 1) {
            $this->successful[] = "Цитата id{$id} удалена.";
            return true;
        } else {
            $this->errors[] = "Не удалось удалить цитату id{$id}";
            return false;
        }
    }

    public function saveQuote($formContent)
    {
        ErrorHandler::ensure($this->checkID($formContent['idInDB'], 'quotes'), "Цитата id{$formContent['idInDB']} не найдена в БД");
        
        if (!$this->checkDataForm($formContent['quoteText'])) {
            return false;
        }

        $this->dbh->query("UPDATE `quotes` SET `quote_text` = ?, `author_id` = ?, `source` = ?, `creator` = ? "
                . " WHERE `id` = ?;", 'none', '', array($formContent['quoteText'], $formContent['authorQuoteID'], $formContent['sourceQuote'], $formContent['creatorQuote'], $formContent['idInDB']));

        $this->successful[] = "Изменения в цитате id{$formContent['idInDB']} сохранены";
        return true;
    }

    // проверяет поля формы на валидность
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
    
    // обрезает текст слишком блинных цитат
    private function checkLengthTextQuotes($quotes)
    {
        // получает максимальную допустимую длину текста цитаты
        $config = Config::getInstance();
        $maxTextLength = $config->getConfigElement(Config::CONSTANTS, 'max_text_length');
        
        foreach ($quotes as $keyQuote => $quote) {
            $text = $quote['text'];
            $length = mb_strlen($text);
            
            if ($length > $maxTextLength) {
                $startText = mb_substr($text, 0, $maxTextLength);
                $endText = mb_substr($text, $maxTextLength);
                //$text = $text . "<a href=\"#\">...читать далее...</a>";
                $quotes[$keyQuote]['startText'] = $startText;
                $quotes[$keyQuote]['endText'] = $endText;
                // указывает представлению на то что длина превышена и надо создать кнопку для получения оставшегося текста
                $quotes[$keyQuote]['lengthExceeded'] = true;
                unset($quotes[$keyQuote]['text']);
                
            } else {
                $quotes[$keyQuote]['lengthExceeded'] = false;
            }
        }
        
        return $quotes;
    }

}
