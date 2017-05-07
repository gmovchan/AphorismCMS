<?php

namespace Application\Controllers;

use Application\Core\Controller;

class AuthController extends Controller
{

    /**
     * метод открывает форму аутентификации, если пользователь не прошёл авторизацию
     */
    public function getPage()
    {
        // Авторизация
        if (isset($_POST['send'])) {

            $this->data['login'] = $_POST['login'];

            if (!$this->auth->authentication()) {
                $this->error = $this->auth->getErrors();
            }
        }

        // Выход
        if (isset($_GET['exit'])) {
            $this->auth->exit_user();
        }

        if ($this->auth->authorization()) {
            /*
            $this->data['login'] = $this->auth->getLogin();
            $this->view->generate('/auth/successfulAuth.php', 'authTemplate.php', $this->data, $this->error);
             * 
             */
            $url = 'Location: /ads/';
            header($url);
        } else {
            $this->view->generate('/auth/authForm.php', 'authTemplate.php', $this->data, $this->error);
        }
    }

    public function logout()
    {
        $this->auth->exit_user();
    }

}
