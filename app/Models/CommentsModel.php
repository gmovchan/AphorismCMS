<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Core\Mysql;
use Application\Core\Config;
use Application\Core\ErrorHandler;
use Application\Core\Notificator;

class CommentsModel extends Model
{
    private $notificator;

    public function __construct()
    {
        $this->dbh = new Mysql(Config::UNMARRIED);
        $this->notificator = new Notificator;
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
        
        $quoteID = $formContent['idInDB'];

        $result = $this->dbh->query("INSERT INTO `comments` (`comment_text`, `author_name`, `quote_id`) "
                . "VALUES (?, ?, ?)", 'rowCount', '', array($formContent['comment'], $formContent['name'], $quoteID));

        if ($result === 1) {
            $this->successful[] = "Комментарий успешно добавлен";
            $this->notificator->sendMailNotification('comment', $quoteID, $formContent['comment']);
            return true;
        } else {
            $this->errors[] = "Не удалось добавить комментарий.";
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

    public function delComment($id)
    {
        ErrorHandler::ensure(!is_null($id), "Не удалось получить id комментария");
        $delete = $this->dbh->query("DELETE FROM `comments` WHERE `id` = ?;", 'rowCount', '', array($id));
        if ($delete === 1) {
            $this->successful[] = "Комментарий удален.";
            return true;
        } else {
            $this->errors[] = "Не удалось удалить комментарий.";
            return false;
        }
    }
    
    // подсчитывает колличество комментариев у цитаты
    public function countComments($quote_id)
    {
         $count = $this->dbh->query("SELECT * FROM `comments` WHERE `quote_id` = ?;", 'rowCount', '', array($quote_id));
         return $count;
    }

}
