<?php
namespace App\Controller;

use App\Model\User;
use Base\AbstractController;
use App\Model\SmsCode;

class Cabinetnotify extends AbstractController
{
    public function index()
    {
        if ($this->getUser()) {
            return $this->view->render(
                'cabinetnotify.phtml',
                [
                    'title' => 'Личный кабинет',
                    'user' => $this->getUser(),
                ]
            );
        }
        return $this->view->render(
            'cabinetnotify.phtml',
            [
                'title' => 'Настройка уведомлений',
                'user' => $this->getUser(),
            ]
        );
    }
}
