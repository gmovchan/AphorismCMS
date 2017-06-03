<?php

namespace Application\Core;

use Application\Core\Config;
use Application\Core\ErrorHandler;

/**
 * Отправляет уведомления администратору
 */
class Notificator
{

    private $adminMail;

    public function __construct()
    {
        $config = Config::getInstance();
        $this->adminMail = $config->getConfigElement(Config::CONSTANTS, 'admin_mail');
    }

    public function sendMailNotification($type, $id = null, $message = null)
    {
        switch ($type) {
            case 'comment':
                $subject = "Bobylquote. Новый комментарий";
                $link = "http://bobylquote.ru/quote/comments?quote_id=$id";
                $message = htmlspecialchars($message, ENT_QUOTES);
                $mailMessage = "Появился новый комментарий к цитате. $link\r\nТекст комментария: $message";

                break;

            case 'offer':
                $subject = "Bobylquote. Предложили новую цитату";
                $link = "http://bobylquote.ru/admin/offer/editoffer?offer_id=$id";
                $mailMessage = "Вам предложили добавить цитату. $link";

                break;

            case 'messageToAdmin':
                $subject = "Bobylquote. Сообщение от посетителя";
                $mailMessage = "Текст сообщения $link";

                break;

            case 'exception':
                $subject = "Bobylquote. Произошла фатальная ошибка.";
                $mailMessage = "Содержание ошибки: $message";

                break;

            default:
                ErrorHandler::ensure(false, "Тип \"$type\" оповещения не поддерживается.");
                break;
        }

        // отправляет письмо и вернет исключение в случае ошибки
        ErrorHandler::ensure(mail($this->adminMail, $subject, $mailMessage), "Не удалось отправить письмо \"$type\" администратору.");

        return true;
    }

}
