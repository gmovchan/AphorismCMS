<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;
use Application\Models\ConfigModel;
use Application\Core\Request;

class QuotesModel extends Model
{

    //private $quotesArray = array();
    //private $authorsArray = array();
    private $dbh;
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->dbh = new MysqlModel(ConfigModel::UNMARRIED);
        /*
        $this->quotesArray = $this->getJson($filePath);

        if (empty($this->quotesArray)) {
            throw new Exception("Объект с цитатами пустой");
        }

        $this->authorsArray = $this->getAuthors();
         * 
         */
    }
    /*
    private function getJson($filename)
    {
        if (file_exists($filename)) {
            $jsonFile = file_get_contents($filename, 'r');
            $dataArray = json_decode($jsonFile, true);
            return $dataArray;
        } else {
            throw new Exception("Файл не найден");
        }
    }

    public function printQuotesFromJson()
    {

        foreach ($this->quotesArray as $value) {
            $quotes = $value['quotes'];

            foreach ($quotes as $quote) {
                echo '<blockquote>';

                if (!empty($quote['quote'])) {
                    echo "<p>" . $quote['quote'] . "</p>";
                }

                if (!empty($quote['author'])) {
                    echo "<p><i>" . $quote['author'] . "</i></p>";
                }

                echo '</blockquote>';
                echo '<br>';
            }
        }
    }

    private function getAuthors()
    {
        $authors = array();

        foreach ($this->quotesArray as $key => $value) {
            $quotes = $value['quotes'];

            foreach ($quotes as $quote) {
                if (!empty($quote['author'])) {
                    $authors[] = trim($quote['author']);
                }
            }
        }

        if (empty($authors)) {
            throw new Exception("Авторы не найдены");
        }

        // избавляется от дубликатов
        $authors = array_unique($authors);

        return $authors;
    }

    public function printAuthors()
    {
        echo 'Всего атворов: ' . count($this->authorsArray) . '<br>';

        foreach ($this->authorsArray as $author) {
            echo $author . '<br>';
        }
    }

    // добавляет всех авторов из JSON файла в MySQL
    public function addAllAuthorsToDB()
    {
        $countNew = 0;
        $countRepeat = 0;

        foreach ($this->authorsArray as $author) {
            if ($this->dbh->query("SELECT * FROM `authors` WHERE `name` = ?;", 'num_row', '', array($author)) !== 0) {
                $countRepeat++;
                continue;
            }
            $this->dbh->query("INSERT INTO `authors` (`name`) VALUES (?)", 'none', '', array($author));
            $countNew++;
        }

        echo "Добавлено $countNew новых авторов<br>";
        echo "Найдено повторов $countRepeat<br>";
    }

    public function addAllQoutesToDB()
    {
        // если таблица не пустая, то цитаты добавляться не будут
        $countRecords = $this->dbh->query("SELECT COUNT(*) FROM `quotes`", 'accos', '', array());
        $this->ensure($countRecords["COUNT(*)"] == 0, "Таблица `qoutes` не пустая");

        $countQuotes = 0;
        $countAuthors = 0;

        foreach ($this->quotesArray as $value) {
            $quotes = $value['quotes'];

            foreach ($quotes as $quote) {

                if (!empty($quote['quote'])) {
                    $author = trim($quote['author']);

                    if (!empty($quote['author'])) {
                        $authorID = $this->getAuthorID($author);

                        if (!empty($authorID)) {
                            $countAuthors++;
                        } else {
                            // 157 ID неизвестного автора
                            $authorID = 157;
                        }
                    } else {
                        $authorID = 157;
                    }

                    $this->dbh->query("INSERT INTO `quotes` (`qoute_text`, `author_id`) VALUES (?, ?)", 'none', '', array($quote['quote'], $authorID));
                    $countQuotes++;
                }
            }
        }

        echo "Добавлено $countQuotes новых цитат<br>";
        echo "Найдено $countAuthors авторов<br>";
    }
    

    private function getAuthorID($name)
    {
        $name = trim($name);
        $authorID = $this->dbh->query("SELECT `id` FROM `authors` WHERE `name` = ?;", 'accos', '', array($name));
        if (isset($authorID['id'])) {
            return $authorID['id'];
        } else {
            return '';
        }
    }

    public function printQuotesFromDB()
    {
        // связывает таблицу Цитаты с таблицей Авторы с помощью ID автора
        $quotes = $this->dbh->query("SELECT quotes.qoute_text AS `text`, authors.name AS "
                . "`author` FROM quotes JOIN authors ON quotes.author_id=authors.id;", 'fetchAll', '', array());

        foreach ($quotes as $quote) {
            echo '<blockquote>';

            if (!empty($quote['text'])) {
                echo "<p>" . $quote['text'] . "</p>";
            }

            if (!empty($quote['author'])) {
                echo "<p><i>" . $quote['author'] . "</i></p>";
            }

            echo '</blockquote>';
            echo '<br>';
        }
    }
     * 
     */
    
    public function getAllQuotes()
    {
        $quotes = $this->dbh->query("SELECT quotes.qoute_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON quotes.author_id=authors.id;",
                'fetchAll', '');
        
        return $quotes;
        //var_dump($quotes);
    }

    public function getQuote()
    {
        $id = $this->request->getProperty('quote_id');

        if (is_null($id)) {
            $id = $this->getRandomQouteID();
        }

        $quote = $this->dbh->query("SELECT quotes.qoute_text AS `text`, quotes.id AS `quote_id`, authors.name "
                . "AS `author`, authors.id AS author_id FROM quotes JOIN authors ON quotes.author_id=authors.id WHERE quotes.id = ?;",
                'accos', '', array($id));

        $randomID = $this->getRandomQouteID();

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
    private function getRandomQouteID()
    {
        $countRows = $this->dbh->query("SELECT COUNT(*) FROM `quotes`;", 'accos', '', array());
        $countRows = $countRows['COUNT(*)'];
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

}