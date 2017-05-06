<?php

require_once 'mysql.php';

/**
 * Класс получает цитаты из JSON файла и содержит методы для дальнейшей работы с ними
 */
class Qoutes
{

    private $quotesArray = array();
    private $authorsArray = array();
    private $dbh;

    public function __construct($filePath)
    {
        $this->dbh = new MysqlModel(ConfigModel::UNMARRIED);

        $this->quotesArray = $this->getJson($filePath);

        if (empty($this->quotesArray)) {
            throw new Exception("Объект с цитатами пустой");
        }

        $this->authorsArray = $this->getAuthors();
    }

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

    public function printQuotes()
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

    // централизованная проверка условия и вызов исключения
    private function ensure($expr, $message)
    {

        if (!$expr) {
            throw new Exception($message);
        }
    }

}

$qotes = new Qoutes('./doc/quotes.json');
$qotes->addAllAuthorsToDB();
$qotes->addAllQoutesToDB();
