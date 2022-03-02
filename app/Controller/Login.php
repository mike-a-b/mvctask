<?php
namespace App\Controller;

use App\Model\User;
use Base\AbstractController;

class Login extends AbstractController
{
    public function index()
    {
        if ($this->getUser()) {
            $this->redirect('/i.phtml');
        }
        return $this->view->render(
            'auth-login.phtml',
            [
                'title' => 'Вход в личный кабинет',
                'user' => $this->getUser(),
            ]
        );
    }

    public function auth()
    {
        $email = (string) $_POST['email'];
        $password = (string) $_POST['password'];

        $user = User::getByEmail($email);
        if (!$user) {
            return 'Неверный логин и пароль';
        }

        if ($user->getPassword() !== User::getPasswordHash($password)) {
            return 'Неверный логин и пароль';
        }

        $this->session->authUser($user->getId());

        $this->redirect('/blog');
    }

    public function register()
    {
        if ($this->getUser()) {
            $this->redirect('/i.phtml');
        }
        return $this->view->render(
            'auth-register.phtml',
            [
                'title' => 'Регистрация пользователя',
                'user' => $this->getUser(),
            ]
        );
//        $name = (string) $_POST['name'];
//        $email = (string) $_POST['email'];
//        $password = (string) $_POST['password'];
//        $password2 = (string) $_POST['password_2'];
//
//        if (!$name || !$password) {
//            return 'Не заданы имя и пароль';
//        }
//
//        if (!$email) {
//            return 'Не задан email';
//        }
//
//        if ($password !== $password2) {
//            return 'Введенные пароли не совпадают';
//        }
//
//        if (mb_strlen($password) < 5) {
//            return 'Пароль слишком короткий';
//        }
//
//        $userData = [
//            'name' => $name,
//            'created_at' => date('Y-m-d H:i:s'),
//            'password' => $password,
//            'email' => $email,
//        ];
//        $user = new User($userData);
//        $user->save();
//
//        $this->session->authUser($user->getId());
//        $this->redirect('/blog');
    }
}