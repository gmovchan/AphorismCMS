<?php

namespace Application\Model;

use Application\Core\Request;
use Application\Core\Model;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class CaptchaModel extends Model
{

    private $request;
    private $captchaBuilder;

    public function __construct()
    {
        $this->request = new Request;
        $this->captchaBuilder = new CaptchaBuilder;
        $this->captchaBuilder->build();
    }

    // возвращает картинку и записывает текст на картинке в переменную сессии
    public function getCaptchaImg()
    {
        $getArray = $this->request->getProperty('GET');

        if (!isset($getArray['uri'])) {
            return null;
        }
        
        // именем ключа является ссылка на страницу, чтобы для каждой страницы была своя капча
        $thisURI = urldecode($getArray['uri']);
        // стартует сессию, если её нет, и сохраняет в её переменной текст капчи
        $this->request->setSessionProperty($thisURI, array('phrase' => $this->captchaBuilder->getPhrase()));
        return $this->captchaBuilder->output();
    }

    public function checkCaptcha($captchaStrForm)
    {

        if (!empty($captchaStrForm)) {
            $this->errors[] = "Каптча неправильная";
        } else {
            $this->errors[] = "Вы не ввели каптчу";
        }

        $thisURI = $this->request->getURI();
        $captchaArraySession = $this->request->getSessionProperty($thisURI);

        // проверяет сохранена ли в сессии капча для этой страницы. Например, сессия может просрочиться
        if (is_null($captchaArraySession)) {
            return false;
        }

        $captchaStrSession = $captchaArraySession['phrase'];
        $this->request->unsetSessionProperty($thisURI);

        /*
         * if ($this->captchaBuilder->testPhrase($captchaStrForm)) {
         *   return true;
         * }
         * Почему-то эта конструкция не работает, метод testPhrase сравнивает старую капчу с новой,
         * с которой надо сравнивать только после следуюзей отправки формы
         * Поэтому, дальше идет переделанный код из этого метода, где требуется метод niceize из другого класса
         * для форматирования строки
         */
        $phraseBuilder = new PhraseBuilder;

        if ($phraseBuilder->niceize($captchaStrSession) == $phraseBuilder->niceize($captchaStrForm)) {
            return true;
        }

        return false;
    }

}
