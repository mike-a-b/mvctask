<?php
namespace  App\Model;

use Base\Db;

class SmsCode
{
    private $id;
    private $mobileNumber;
    private $value;
    private $created_at;

    public function __construct(array $data) {
        $this->mobileNumber = $data['phone'];
        $this->value = $data['value'];
        $this->created_at = $data['created_at'];
    }

    public function save() : int
    {
        $db = Db::getInstance();
        $res = $db->exec(
            'INSERT INTO smscodes (
                    value,
                      created_at,
                      phone
                    ) VALUES  (
                               :value,
                               :created_at,
                               :phone
                    )',
            __FILE__,
            [
                ':created_at' => $this->created_at,
                ':value' => $this->value,
                ':phone'=>$this->mobileNumber
            ]
        );
        $this->id  = $db->lastInsertId();
        return $res;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    /**
     * @param mixed $mobileNumber
     */
    public function setMobileNumber($mobileNumber): void
    {
        $this->mobileNumber = $mobileNumber;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getIsLogin()
    {
        return $this->isLogin;
    }

    /**
     * @param mixed $isLogin
     */
    public function setIsLogin($isLogin): void
    {
        $this->isLogin = $isLogin;
    }
}