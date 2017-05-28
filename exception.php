<?php

function exception_handler($exception) {
  echo "Неперехваченное исключение: " , $exception->getMessage(), "\n";
}

set_exception_handler('exception_handler');

throw new Exception('Неперехваченное исключение');
echo "Не выполнено\n";
