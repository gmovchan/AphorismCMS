<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;
use Application\Models\ConfigModel;

class CommentsModel extends Model
{

    private $dbh;

    public function __construct()
    {
        $this->dbh = new MysqlModel(ConfigModel::UNMARRIED);
    }

    public function getComments($quoteID)
    {
        $comments = $this->dbh->query("SELECT * FROM `comments` WHERE `quote_id` = ? "
                . "ORDER BY time_creation ASC;", 'fetchAll', '', array($quoteID));
        $comments = $this->parseCommentsTime($comments);
        return $comments;
    }
    
    // преобразует в массив дату создания коммента, полученную из БД
    private function parseCommentsTime($comments)
    {
        if (!empty($comments)) {
            foreach ($comments as $keyComment => $valueComment) {
                $comments[$keyComment]['timeArray'] = date_parse($valueComment['time_creation']);
            }
        }
        
        return $comments;
    }
    
    // FIXME: дата создания комментария автоматически проставляется в БД, 
    // а там неверно указан часовой пояс
    public function addComment($formContent)
    {

        $formContent = $this->validationDataForm($formContent);

        if (is_null($formContent)) {
            return false;
        }

        if (empty($formContent['name'])) {
            $formContent['name'] = "Аноним";
        }

        $result = $this->dbh->query("INSERT INTO `comments` (`comment_text`, `author_name`, `quote_id`) "
                . "VALUES (?, ?, ?)", 'rowCount', '', array($formContent['comment'], $formContent['name'], $formContent['idInDB']));

        if ($result === 1) {
            $this->successful[] = "Цитата успешно добавлена";
            return true;
        } else {
            $this->errors[] = "Не удалось добавить цитату в БД";
            return false;
        }
    }

    /*
     * проверяет соответствие полей формы заданным условиям
     */

    private function validationDataForm($formContent)
    {
        if (empty($formContent['comment'])) {
            $this->errors[] = "Текст цитаты не введен";
            return null;
        }

        if (iconv_strlen($formContent['comment']) > 15000) {
            $this->errors[] = "Текст длиннее 15 000 символов";
            return null;
        }

        if (empty($formContent['name'])) {
            $formContent['name'] = "Аноним";
        }

        return $formContent;
    }

}
