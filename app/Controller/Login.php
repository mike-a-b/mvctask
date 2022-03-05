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
        $phone = (string) $_POST['mobileNumber'];
        $password = (string) $_POST['password'];
        $name = '';
        $email = '';

        $isSaveLogin = (boolean) $_POST['savelogin'];
        if($isSaveLogin)
        {
            $this->session->savelogin($phone, $password);
        }

        $this->redirect('/cabinet');
    }

    public function smsSend()
    {
        header('Content-Type: application/json');
        $rand = rand(1000, 9999);
        if(isset($_POST['mobileNumber'])) {
            $vowels = array("+", "(", ")", "-", " ");
            $mobileNumber = str_replace($vowels, "", $_POST['mobileNumber']);
            $result = file_get_contents('https://smsc.ru/sys/send.php?login='.'acidburn'.
                '&psw='.'m1k31tm1k31t'.'&phones='.$mobileNumber.'&mes='.
                'CODE VREMYAITB.RU: '. $rand );
            $smsData = [
                'created_at' => date('Y-m-d H:i:s'),
                'value' => $rand,
                'phone' => $mobileNumber,
            ];
            $code = new SmsCode($smsData);
            //запись в бд текущего кода отправленного
            if($code->save()) {
                echo json_encode([
                    'success' => 1,
                    'error' => 0,
                    'code' => $rand,
                ]);
            }
        } else {
            echo json_encode([
                'success' => 0,
                'error' => 1,
                'code' => $rand,
            ]);
        }
    }
}