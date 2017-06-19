<?php

namespace Application\Core;

class Request
{

    private $properties;
    // канал связи для передачии информации из классов-контроллеров пользователю
    private $feedback = array();

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        /*
          if (!isset($_SESSION)) {
          $this->properties['SESSION'] = $_SESSION;
          }
         * 
         */

        if (isset($_SERVER['REQUEST_METHOD'])) {

            /*
             * содержимое переменных POST и GET отправляется в одноименный массив
             * чтобы передать его из контроллера в модель одной переменной
             * а не получать каждое значение по одному с помощью getProperty
             */
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $this->properties['POST'] = $_REQUEST;
                    return;
                    break;

                case 'GET':
                    $this->properties['GET'] = $_REQUEST;
                    return;
                    break;

                default:
                    // $_REQUEST поумолчанию содержит данные суперглобальных переменных
                    $this->properties = $_REQUEST;
                    return;
                    break;
            }
        }

        /*
         * 
          // работа с аргументами командной строки
          foreach ($_SERVER['argv'] as $arg) {
          if (strpos($arg, '=')) {
          list($key, $val) = explode("=", $arg);
          $this->setProperty($key, $val);
          }
          }
         * 
         */
    }

    public function getProperty($key)
    {
        if (isset($this->properties[$key])) {
            return $this->properties[$key];
        }

        return null;
    }

    public function setProperty($key, $val)
    {
        $this->properties[$key] = $val;
    }

    public function addFeedback($msg)
    {
        array_push($this->feedback, $msg);
    }

    public function getFeedbackString()
    {
        return $this->feedback;
    }
    
    public function getSessionProperty($key)
    {
        $this->startSession();

        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return null;
    }

    public function setSessionProperty($key, $val)
    {
        $this->startSession();
        $_SESSION[$key] = $val;
    }

    public function unsetSessionProperty($key)
    {
        $this->startSession();

        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    private function startSession()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
    }
    
    public function getURI()
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    public function getHostAdress()
    {
        // если скрипт вызывают через командную строку, то переменная $_SERVER['HTTP_HOST'] 
        // отсутствует и обращение к ней напрямую вызывает ошибку
        if (isset($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        } else {
            return '';
        }
    }

}
