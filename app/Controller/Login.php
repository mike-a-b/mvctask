<?php
namespace App\Controller;

use App\Model\User;
use Base\AbstractController;
use App\Model\SmsCode;

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

    public function smsSend()
    {
        header('Content-Type: application/json');
        $rand = rand(1000, 9999);
        if(isset($_POST['mobileNumber'])) {
            $vowels = array("+", "(", ")", " ");
            $mobileNumber = str_replace($vowels, "", $_POST['mobileNumber']);
//            $result = file_get_contents('https://smsc.ru/sys/send.php?login=acidburn&psw=m1k31tm1k31t&phones=79689892109&mes=1234');
            $code = new SmsCode();

            //запись в бд текущего кода отправленного
            echo json_encode([
                'success' => 1,
                'error' => 0,
                'code' => $rand,
            ]);
        } else {
            echo json_encode([
                'success' => 0,
                'error' => 1,
                'code' => $rand,
            ]);
        }
    }
}