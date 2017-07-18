<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Core\Mysql;
use Application\Core\Config;
use Application\Core\ErrorHandler;
use Application\Core\Notificator;
use Application\Core\Request;
use Application\Model\CaptchaModel;

class FeedbacksModel extends Model
{
    private $notificator;
    private $request;
    private $captcha;
    private $dbTableName;

    public function __construct()
    {
        $this->dbh = new Mysql(Config::DB);
        $this->notificator = new Notificator;
        $this->captcha = new CaptchaModel;
        $this->request = new Request;
        // эта же модель используется и для отзывов через наследования и замену имени таблицы
        $this->dbTableName = "`feedbacks`";
    }

    public function getComments()
    {
        $comments = $this->dbh->query("SELECT * FROM $this->dbTableName "
                . "ORDER BY time_creation ASC;", 'fetchAll');
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

        $result = $this->dbh->query("INSERT INTO $this->dbTableName (`comment_text`, `author_name`) "
                . "VALUES (?, ?)", 'rowCount', '', array($formContent['comment'], $formContent['name']));

        if ($result === 1) {
            $this->successful[] = "Отзыв успешно добавлен";
            $this->notificator->sendMailNotification('feedback', '', $formContent['comment']);
            return true;
        } else {
            $this->errors[] = "Не удалось добавить отзыв.";
            return false;
        }
    }

    /*
     * проверяет соответствие полей формы заданным условиям
     */

    private function validationDataForm($formContent)
    {
        if (!empty($formContent['email'])) {
            $this->errors[] = "Сработала защита от спама.";
            return null;
        }

        if (empty($formContent['comment'])) {
            $this->errors[] = "Текст цитаты не введен";
            return null;
        }

        if (iconv_strlen($formContent['comment']) > 15000) {
            $this->errors[] = "Текст длиннее 15 000 символов";
            return null;
        }

        // проверка каптчи
        if (!$this->captcha->checkCaptcha($formContent['captcha'])) {
            
            // забирает ошибки из другой модели, чтобы потом вывести их в представление
            foreach ($this->captcha->getErrors() as $value) {
                $this->errors[] = $value;
            }

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
        $delete = $this->dbh->query("DELETE FROM $this->dbTableName WHERE `id` = ?;", 'rowCount', '', array($id));
        if ($delete === 1) {
            $this->successful[] = "Отзыв удален.";
            return true;
        } else {
            $this->errors[] = "Не удалось удалить комментарий.";
            return false;
        }
    }

}
