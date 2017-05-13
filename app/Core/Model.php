<?php

namespace Application\Core;

/**
 * Содержит основную логику приложения - вычисления, запросы к БД и обработку полученных из БД данных.
 * Все то, чего не должно быть в Роутере, Контроллере или Представлении :) 
 */
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
    /*
     * FIXME: возможно стоит вынести в отдельный класс. Но это не точно, потому что пока
     * не думал как тогда реализовать для каждого класса свой класс исключения, если понадобится
     */
    protected function ensure($expr, $message)
    {

        if (!$expr) {
            throw new \Exception($message);
        }
    }

}
