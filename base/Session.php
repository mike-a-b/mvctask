<?php
namespace Base;

class Session
{
    public function init()
    {
        session_start();
    }

    public function authUser(int $id)
    {
        $_SESSION['user_id'] = $id;
    }

    public function getUserId()
    {
        return $_SESSION['user_id'] ?? false;
    }

    public function savelogin(string $phone, string $pass)
    {
        $_SESSION['phone'] = $phone;
        $_SESSION['password'] = $pass;
    }
}