<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;
use Application\Models\ConfigModel;
use Application\Core\Request;
use Application\Models\AuthorsModel;

class QuotesModel extends Model
{

    private $dbh;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->dbh = new MysqlModel(ConfigModel::UNMARRIED);
    }

    public function getAllQuotes()
    {
        $quotes = $this->dbh->query("SELECT quotes.quote_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON quotes.author_id=authors.id;", 'fetchAll', '');

        return $quotes;
    }

    // Получает цитату по её id
    public function getQuote($id)
    {
        $quote = $this->dbh->query("SELECT quotes.quote_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON quotes.author_id=authors.id WHERE quotes.id = ?;", 'accos', '', array($id));

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
        $countRows = $this->dbh->query("SELECT COUNT(*) FROM `quotes`;", 'accos', '', array());
        $countRows = $countRows[0];
        // отнял 1 из-за смещения при использовании LIMIT
        $randRow = rand(1, $countRows) - 1;
        $id = $this->dbh->query("SELECT `id` FROM `quotes` LIMIT $randRow, 1;", 'accos', '', array());
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
    public function addQuote()
    {
        $quoteForm = array();
        $quoteForm['quoteText'] = $this->request->getProperty('quoteText');
        $quoteForm['authorQuoteID'] = $this->request->getProperty('authorQuoteID');
        $quoteForm['sourceQuote'] = $this->request->getProperty('sourceQuote');
        $quoteForm['creatorQuote'] = $this->request->getProperty('creatorQuote');

        if (!$this->checkDataForm($quoteForm['quoteText'])) {
            return false;
        }

        $this->dbh->query("INSERT INTO `quotes` (`quote_text`, `author_id`, `source`, `creator`) "
                . "VALUES (?, ?, ?, ?)", 'none', '', array($quoteForm['quoteText'], $quoteForm['authorQuoteID'], $quoteForm['sourceQuote'], $quoteForm['creatorQuote']));
        $this->successful[] = "Цитата успешно добавлена";
        return true;
    }

    // Удалить цитату из БД
    public function delQuote()
    {
        $id = $this->request->getProperty('quote_id');
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
        $query = $this->dbh->query("SELECT * FROM `quotes` WHERE `id` = ?;", 'num_row', '', array($id));
        return $query === 1;
    }

    public function quoteEditSave()
    {
        //FIXME: надо упростить эту конструкцию, переписав Request на хранение содержимого POST переменной в отдельном массивк
        // чтобы он извлекался и передавался из контроллера
        $quoteForm = array();
        $quoteForm['quoteText'] = $this->request->getProperty('quoteText');
        $quoteForm['authorQuoteID'] = $this->request->getProperty('authorQuoteID');
        $quoteForm['sourceQuote'] = $this->request->getProperty('sourceQuote');
        $quoteForm['creatorQuote'] = $this->request->getProperty('creatorQuote');
        $quoteForm['quoteID'] = $this->request->getProperty('quote_id');

        $this->ensure($this->checkID($quoteForm['quoteID']), "Цитата id{$quoteForm['quoteID']} не найдена в БД");
        
        if (!$this->checkDataForm($quoteForm['quoteText'])) {
            return false;
        }

        $this->dbh->query("UPDATE `quotes` SET `quote_text` = ?, `author_id` = ?, `source` = ?, `creator` = ? "
                . " WHERE `id` = ?;", 'none', '', array($quoteForm['quoteText'], $quoteForm['authorQuoteID'], $quoteForm['sourceQuote'], $quoteForm['creatorQuote'], $quoteForm['quoteID']));

        $this->successful[] = "Изменения в цитате id{$quoteForm['quoteID']} сохранены";
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
