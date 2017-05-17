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

}
