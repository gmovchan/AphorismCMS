<?php

$quotes = getJson('./doc/quotes.json');
printQuote($quotes);

function printQuote($quotes)
{
    foreach ($quotes as $key => $value) {
        $quotes = $value['quotes'];
        foreach ($quotes as $quote) {
            echo '<blockquote>';
            if (!empty($quote['quote'])) {
                echo "<p>".$quote['quote']."</p>";
            }
            if (!empty($quote['author'])) {
                echo "<p><i>".$quote['author']."</i></p>";
            }
            echo '</blockquote>';
            echo '<br>';
        }
    }
}

function getJson($filename)
{
    if (file_exists($filename)) {
        $jsonFile = file_get_contents($filename, 'r');
        $dataArray = json_decode($jsonFile, true);
        return $dataArray;
    } else {
        throw new Exception("Файл не найден!");
    }
    
}