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
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON quotes.author_id=authors.id;",
                'fetchAll', '');
        
        return $quotes;
    }

    public function getQuote()
    {
        // id запрашиваемой цитаты хранится в request, который получает её из GET пременной
        // либо можно поменять это значение в контроллере с помощью соответсвующего метода 
        // объекта Request
        $id = $this->request->getProperty('quote_id');

        if (is_null($id)) {
            $id = $this->getRandomQuoteID();
        }

        $quote = $this->dbh->query("SELECT quotes.quote_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON quotes.author_id=authors.id WHERE quotes.id = ?;",
                'accos', '', array($id));

        $randomID = $this->getRandomQuoteID();

        if ($quote) {
            // получает предыдущий и следующий ID, нужны для перехода вперед и назад
            // без этого, если удалить какую-то позицию - переход может сломаться
            $prevAndNextIDs = $this->dbh->query("SELECT quotes.id FROM `quotes` WHERE (`id` = (SELECT MAX(`id`) "
                    . "FROM `quotes` WHERE `id` < ?) OR `id` = (SELECT MIN(`id`) FROM `quotes` WHERE `id` > ?));", 
                    'fetchAll', '', array($quote['quote_id'], $quote['quote_id']));
            
            // условные операторы на случай если цитата будет первой или последней
            // чтобы правило отобразить кнопки перехода вперед-назад
            if ((int) $id === 1) {
                $quote['previous_id'] = 0;
                $quote['next_id'] = 2;
            } elseif ($id > 1 && count($prevAndNextIDs) === 1) {
                $quote['previous_id'] = $prevAndNextIDs[0]['id'];
                $quote['next_id'] = 0;
            } else {
                $quote['previous_id'] = $prevAndNextIDs[0]['id'];
                $quote['next_id'] = $prevAndNextIDs[1]['id'];
            }

            $quote['random_id'] = $randomID;
            return $quote;
        } else {
            return array('text' => 'Цитата не найдена',
                'quote_id' => 0,
                'author' => 'Администратор',
                'author_id' => 157,
                'previous_id' => 0,
                'next_id' => 0,
                'random_id' => $randomID);
        }
    }

    // возвращает ID случайной цитаты
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
            return NULL;
        }
    }
    
    // добавляет новую цитату, должна вызываться только из панели администратора
    public function addQuote()
    {
        $addQuoteForm = array();
        
        $addQuoteForm['quoteText'] = $this->request->getProperty('quoteText');
        $addQuoteForm['authorQuoteID'] = $this->request->getProperty('authorQuoteID');
        $addQuoteForm['sourceQuote'] = $this->request->getProperty('sourceQuote');
        $addQuoteForm['creatorQuote'] = $this->request->getProperty('creatorQuote');
        
        if (empty($addQuoteForm['quoteText'])) {
            $this->errors[] = "Текст цитаты не введен";
            return false;
        }
        
        if (iconv_strlen ($addQuoteForm['quoteText']) > 15000) {
            $this->errors[] = "Текст длиннее 15 000 символов";
            return false;
        }
        
        // если авторство отсутствует, то ему будет присвоено id автора "низвестен"
        if (empty($addQuoteForm['authorQuoteID'])) {
            //$addQuoteForm['authorQuoteID'] = 157; 
        }
        

        $this->dbh->query("INSERT INTO `quotes` (`quote_text`, `author_id`, `source`, `creator`) VALUES (?, ?, ?, ?)", 
                'none', '', array($addQuoteForm['quoteText'], $addQuoteForm['authorQuoteID'], $addQuoteForm['sourceQuote'], $addQuoteForm['creatorQuote']));
        
        $this->successful[] = "Цитата успешно добавлена";
        
        return true;
    }

}