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
        $hostLink = "http://bobylquote.ru";
        switch ($type) {
            case 'comment':
                $subject = "Bobylquote. Новый комментарий";
                $link = "$hostLink/quote/comments?quote_id=$id";
                $adminLink = "$hostLink/admin/quote/comments?quote_id=$id";
                $message = htmlspecialchars($message, ENT_QUOTES);
                $mailMessage = "Появился новый комментарий к цитате.\r\nТекст комментария: $message\r\nПосмотреть $link\r\nРедактировать $adminLink";

                break;

            case 'offer':
                $subject = "Bobylquote. Предложили новую цитату";
                $link = "$hostLink/admin/offer/editoffer?offer_id=$id";
                $mailMessage = "Вам предложили добавить цитату. $link";

                break;

            case 'messageToAdmin':
                $subject = "Bobylquote. Сообщение от посетителя";
                $mailMessage = "Текст сообщения $message";

                break;

            case 'exception':
                $subject = "Bobylquote. Произошла фатальная ошибка.";
                $mailMessage = "Содержание ошибки: $message";

                break;

            default:
                ErrorHandler::ensure(false, "Тип \"$type\" оповещения не поддерживается.");
                break;
        }

        // если проект запущен на тестовом сервере, то письма не будут отправляться
        $appStatus = ErrorHandler::getConfigElement('app_in_production');

        if ($appStatus === 1) {
            // отправляет письмо и вернет исключение в случае ошибки
            ErrorHandler::ensure(mail($this->adminMail, $subject, $mailMessage, '', '-fno-reply@bobylquote.ru'), "Не удалось отправить письмо \"$type\" администратору.");
        }

        return true;
    }

}
