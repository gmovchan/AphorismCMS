<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;
use Application\Models\ConfigModel;
use Application\Models\AuthorsModel;

class QuotesModel extends Model
{

    private $dbh;

    public function __construct()
    {
        $this->dbh = new MysqlModel(ConfigModel::UNMARRIED);
    }

    public function getAllQuotes($author = null)
    {
        if (is_null($author)) {
            $quotes = $this->dbh->query("SELECT quotes.quote_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON quotes.author_id=authors.id  ORDER BY quotes.id DESC;", 'fetchAll', '');
        } else {
            $quotes = $this->dbh->query("SELECT quotes.quote_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON "
                    . "quotes.author_id=authors.id  WHERE authors.id = ? ORDER BY quotes.id DESC;", 'fetchAll', '', array($author));
        }
        
        return $quotes;
    }

    // Получает цитату по её id
    public function getQuote($id)
    {
        $quote = $this->dbh->query("SELECT quotes.quote_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON quotes.author_id=authors.id WHERE quotes.id = ?;", 'fetch', '', array($id));

        if ($quote) {

            $prevAndNextRows = $this->getPrevAndNextRows($quote['quote_id']);
            $quote['previous_id'] = $prevAndNextRows['previous_id'];
            $quote['next_id'] = $prevAndNextRows['next_id'];
            $quote['random_id'] = $this->getRandomQuoteID();
            return $quote;
        } else {
            return false;
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

    // Возвращает цитату по умолчанию, обычно используется, если цитата не найдена
    public function getEmptyQuote()
    {
        return array('text' => 'Цитата не найдена. Неверный id.',
            'quote_id' => 0,
            'author' => 'Администратор',
            'author_id' => 157,
            'previous_id' => 0,
            'next_id' => 0,
            'random_id' => $this->getRandomQuoteID());
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
        $this->ensure(!is_null($id), "Не удалось получить id удаляемой цитаты");
        $delete = $this->dbh->query("DELETE FROM `quotes` WHERE `id` = ?;", 'rowCount', '', array($id));
        if ($delete === 1) {
            $this->successful[] = "Цитата id{$id} удалена.";
            return true;
        } else {
            $this->errors[] = "Не удалось удалить цитату id{$id}";
            return false;
        }
    }

    // проверяет существование цитаты
    private function checkID($id)
    {
        $query = $this->dbh->query("SELECT * FROM `quotes` WHERE `id` = ?;", 'rowCount', '', array($id));
        return $query === 1;
    }

    public function saveQuote($formContent)
    {
        $this->ensure($this->checkID($formContent['idInDB']), "Цитата id{$formContent['idInDB']} не найдена в БД");
        
        if (!$this->checkDataForm($formContent['quoteText'])) {
            return false;
        }

        $this->dbh->query("UPDATE `quotes` SET `quote_text` = ?, `author_id` = ?, `source` = ?, `creator` = ? "
                . " WHERE `id` = ?;", 'none', '', array($formContent['quoteText'], $formContent['authorQuoteID'], $formContent['sourceQuote'], $formContent['creatorQuote'], $formContent['idInDB']));

        $this->successful[] = "Изменения в цитате id{$formContent['idInDB']} сохранены";
        return true;
    }

    // проверяет поля формы на валидность
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
