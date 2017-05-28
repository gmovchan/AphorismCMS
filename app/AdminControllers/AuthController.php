<?php

namespace Application\AdminControllers;

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
                $this->data['errors'] = $this->auth->getErrors();
            }
        }

        // Выход
        if (isset($_GET['exit'])) {
            $this->auth->exit_user();
        }

        if ($this->auth->authorization()) {
            $url = 'Location: /admin/';
            header($url);
        } else {
            $this->view->generate('/auth/authForm.php', '/authTemplate.php', $this->data);
        }
    }

    public function logout()
    {
        $this->auth->exit_user();
    }

}
