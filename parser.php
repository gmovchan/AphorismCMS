<?php

$quotes = parseQuotes("./doc/quotes.txt");
print_r($quotes);

// сохранить в JSON файл
//var_dump(createJson($quotes));


// разбор файла в массив
function parseQuotes($filename)
{
    $quotesArrayByTitle = array();
    $quotesExplode = array();
    
    if (!file_exists($filename)) {
        throw new Exception("Файл не найден!");
    }
    
    $quotes = file_get_contents($filename);
    
    $quotesArray = explode(PHP_EOL, $quotes);
    
    foreach ($quotesArray as $key => $value) {
        // создает массив, где ключь - заголовок, а значение ключа - строка с цитатами
        if (empty($quotesArray[($key + 1)]) && !empty($quotesArray[($key + 2)]) && empty($quotesArray[($key + 3)]) && empty($quotesArray[($key + 4)])) {
            // разбивает строку с цитатами на массив
            $quotesExplode = explode('//', $quotesArray[($key + 2)]);
            //убираю лишние пробелы в цитате
            foreach ($quotesExplode as $key => $quote) {
                $quote = trim($quote);
                // удаляет пустой элемент
                if (empty($quote)) {
                    unset($quotesExplode[$key]);
                } else {
                    $quoteAndAuthor = getQuoteAndAuthor($quote);
                    $quotesExplode[$key] = $quoteAndAuthor;
                }
            }
            $quotesArrayByTitle[] = array('title' => $value, 'quotes' => $quotesExplode);
        }
    }
    
    return $quotesArrayByTitle;
}
        
// получает автора из строки
function getQuoteAndAuthor($quote)
{
    $quote = explode('.', $quote);
    $lastElement = '';

    // если строка разбита меньше, чем на 2 элемента, то, скорее всего, автор не указан
    if (count($quote) > 2) {
        while (empty($lastElement)) {
            $lastElement = trim(array_pop($quote));
        }
    } else {
        $lastElement = '';
    }


    // восстанавливаю цитату без автора в конце
    $quote = implode('.', $quote) . '.';

    // некоторые цитаты заканчиваются кавычками
    if ($lastElement == '»') {
        $lastElement = '';
        $quote = $quote . '»';
    }

    // некоторые предложения заканчиваются '?' или '!'
    if (strripos($lastElement, '?')) {
        $lastElementQuote = explode('?', $lastElement);
        $lastElement = trim(array_pop($lastElementQuote));
        // восстанавливаю цитату без автора в конце
        $quote = implode('?', $lastElementQuote) . '?';
    } elseif (strripos($lastElement, '!')) {
        $lastElementQuote = explode('!', $lastElement);
        $lastElement = trim(array_pop($lastElementQuote));
        // восстанавливаю цитату без автора в конце
        $quote = implode('!', $lastElementQuote) . '!';
    }

    $lastElement = explode(' ', $lastElement);
    // если последнее предложение строки состоит из 1-2 слов, то это скорее всего автор
    if (count($lastElement) < 3) {
        $author = $lastElement;
    } else {
        $author = '';
    }

    if (!empty($author)) {
        $author = implode(' ', $author);
        $author = trim($author);
    }
    return array('quote' => $quote, 'author' => $author);
}

// получает массив со списком авторов
function getAuthorArray($quotes)
{
    $authors = array();
    foreach ($quotes as $key => $value) {
        $quotes = $value['quotes'];
        foreach ($quotes as $quote) {
            if (!empty($quote['author'])) {
                $authors[] = $quote['author'];
            }
        }
    }
    
    return $authors;
}

function countQuotes($quotes)
{
    $count = 0;
    foreach ($quotes as $key => $value) {
        $quotes = $value['quotes'];
        foreach ($quotes as $quote) {
            if (!empty($quote['quote'])) {
                $count++;
            }
        }
    }
    
    return $count;
}

function createJson($dataArray)
{
    $dataArray = json_encode($dataArray, JSON_UNESCAPED_UNICODE);
    var_dump($dataArray);
    $jsonFile = fopen('quotes.json', 'w+');
    // возвращает false в случае ошибки записи
    if (fwrite($jsonFile, $dataArray)) {
        return true;
    } else {
        return false;
    }
    fclose($jsonFile);
}