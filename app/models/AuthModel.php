<?php

namespace Application\Models;

use Application\Core\Model;
use Application\Models\MysqlModel;
use Application\Models\ConfigModel;

/**
 * класс переписан так, чтобы для аутентификации и авторизации использовались только куки,
 * потому что необходима возможность заходить под одним пользователям с разных компьютеров 
 * и чтобы не слетала аутентификация
 * в приложении не требуется разделение прав
 */
class AuthModel extends Model
{

    // объект для работы с БД
    private $dbh;
    private $userData = array();

    public function __construct()
    {
        // передает класса из которого вызывается, для каждого класса свои
        // настройки mysql
        $this->dbh = new MysqlModel(ConfigModel::ONE_CONFIG);
    }

    /**
     * Авторизация
     */
    public function authorization()
    {
        if (isset($_COOKIE['id_user']) and isset($_COOKIE['password'])) {

            $id_user = $_COOKIE['id_user'];
            $password = $_COOKIE['password'];
        } elseif (!empty($this->userData)) {

            $id_user = $this->userData['id_user'];
            $password = $this->userData['password'];
        } else {
            return false;
        }
        
        // получаю логин, чтобы потом его использовать в view для обращения к пользователю
        // TODO: лучше в таблице сделать отдельную ячейку для имени, которая не
        // участвует в авторизации и аутентификации
        $login = $this->dbh->query("SELECT `login_user` FROM `users` WHERE `id_user` = ?;", 'result', '0', array($id_user));
        $this->userData['login'] = $login;

        if ($this->dbh->query("SELECT * FROM `users` WHERE `id_user` = ? AND `password_user` = ?;", 'num_row', '', array($id_user, $password)) == 1) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Аутентификация
     */
    public function authentication()
    {
        
        $login = htmlspecialchars($_POST['login']);
        $password = md5($_POST['password'] . 'lol');
        
        $users = $this->dbh->query("SELECT * FROM `users` WHERE `login_user` = ? AND `password_user` = ?;", 'fetchAll', '', array($login, $password));
                
        if ( count($users) == 1) {
            $id_user = $users[0]['id_user'];
            //ставим куки на 2 недели
            setcookie("id_user", $id_user, time() + 3600 * 24 * 14, '/', null, false, true);
            setcookie("password", $password, time() + 3600 * 24 * 14, '/', null, false, true);
            
            // передает в сесси информацию, необходимую для аторизации, потому что 
            // куки не будут переданы пока страница не обновится
            $this->userData['id_user'] = $id_user;
            $this->userData['password'] = $password;

            return true;
        } else {

            //пользователь не найден в БД или пароль неверный
            if ($this->dbh->query("SELECT * FROM `users` WHERE `login_user` = ?;", 'num_row', 0, array($login)) == 1) {
                $error[] = 'Неверный пароль';
            } else {
                $error[] = 'Пользователя не сущестует';
            }
            $this->errors = $error;
            return false;
        }
    }

    public function exit_user()
    {
        session_start();
        session_destroy();
        setcookie("login", '', time() - 3600, '/', null, false, true);
        setcookie("password", '', time() - 3600, '/', null, false, true);
        $host = 'http://' . $_SERVER['HTTP_HOST'] . '/auth';
        header("Location:" . $host);
        exit();
    }
   
    public function getLogin() {
        return $this->userData['login'];
    }

}
