<?php

namespace Application\Core;

class Model {

    protected $errors = array();
    protected $successful = array();

    public function getErrors() {
        return $this->errors;
    }
    
    public function getSuccessful()
    {
        return $this->successful;
    }
    
    // централизованная проверка условия и вызов исключения
    protected function ensure($expr, $message)
    {

        if (!$expr) {
            throw new \Exception($message);
        }
    }

}
