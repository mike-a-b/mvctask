<?php
namespace App\Controller;

use App\Model\User;
use Base\AbstractController;
use App\Model\SmsCode;

class Cabinet extends AbstractController
{
    public function index()
    {
        if ($this->getUser()) {
            return $this->view->render(
                'cabinet.phtml',
                [
                    'title' => 'Личный кабинет',
                    'user' => $this->getUser(),
                ]
            );
        }
        return $this->view->render(
            'cabinet.phtml',
            [
                'title' => 'Личный кабинет',
                'user' => $this->getUser(),
            ]
        );
    }
}
