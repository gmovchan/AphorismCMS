<?php

class ViewQoutes
{

    private $quotesArray = array();
    private $authorsArray = array();

    public function __construct($filePath)
    {
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

        foreach ($this->quotesArray as $key => $value) {
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
                    $authors[] = $quote['author'];
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

}

$viewQotes = new ViewQoutes('./doc/quotes.json');
$viewQotes->printQuotes();
$viewQotes->printAuthors();