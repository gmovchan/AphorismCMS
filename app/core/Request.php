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
            // $_REQUEST поумолчанию содержит данные суперглобальных переменных
            $this->properties = $_REQUEST;
            return;
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

    public function getFeedbackString($separator = "<br>")
    {
        return implode($separator, $this->feedback);
    }

}

