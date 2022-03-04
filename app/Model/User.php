<?php
namespace App\Model;

use Base\Db;

class User
{
    private $id;
    private $name;
    private $createdAt;
    private $password;
    private $email;
    private $phone;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->password = $data['password'];;
        $this->createdAt = $data['created_at'];
        $this->email = $data['email'];
        $this->phone = $data['phone'];
    }

    public static function getByEmail(string $email)
    {
        $db = Db::getInstance();
        $data = $db->fetchOne(
            "SELECT * fROM users WHERE email = :email",
            __METHOD__,
            [':email' => $email]
        );
        if (!$data) {
            return null;
        }

        $user = new self($data);
        $user->id = $data['id'];
        return $user;
    }

    public static function getByPhone(string $phone)
    {
        $db = Db::getInstance();
        $data = $db->fetchOne(
            "SELECT * fROM users WHERE phone = :phone",
            __METHOD__,
            [':phone' => $phone]
        );
        if (!$data) {
            return null;
        }

        $user = new self($data);
        $user->id = $data['id'];
        return $user;
    }

    public static function getByIds(array $userIds)
    {
        $db = Db::getInstance();
        $idsString = implode(',', $userIds);
        $data = $db->fetchAll(
            "SELECT * fROM users WHERE id IN($idsString)",
            __METHOD__
        );
        if (!$data) {
            return [];
        }

        $users = [];
        foreach ($data as $elem) {
            $user = new self($elem);
            $user->id = $elem['id'];
            $users[$user->id] = $user;
        }

        return $users;
    }

    public function save()
    {
        $db = Db::getInstance();
        $res = $db->exec(
            'INSERT INTO users (
                    password, 
                    created_at,
                    email,
                   phone
                    ) VALUES (
                    :name, 
                    :password, 
                    :created_at,
                    :email
                    ,:phone
                )',
            __FILE__,
            [
                ':password' => self::getPasswordHash($this->password),
                ':created_at' => $this->createdAt,
                ':email' => $this->email,
                ':phone' =>$this->phone,
            ]
        );

        $this->id = $db->lastInsertId();

        return $res;
    }

    public static function getById(int $id): ?self
    {
        $db = Db::getInstance();
        if(!isset($db)) echo "ERROR CONNECTION ";
        $data = $db->fetchOne("SELECT * fROM users WHERE id = :id", __METHOD__, [':id' => $id]);
        if (!$data) {
            return null;
        }

        $user = new self($data);
        $user->id = $id;
        return $user;
    }


    public static function getList(int $limit = 10, int $offset = 0): array
    {
        $db = Db::getInstance();
        $data = $db->fetchAll(
            "SELECT * fROM users LIMIT $limit OFFSET $offset",
            __METHOD__
        );
        if (!$data) {
            return [];
        }

        $users = [];
        foreach ($data as $elem) {
            $user = new self($elem);
            $user->id = $elem['id'];
            $users[] = $user;
        }

        return $users;
    }

    public static function getPasswordHash(string $password)
    {
        return sha1('.,f.akjsduf' . $password);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function isAdmin(): bool
    {
        return in_array($this->id, ADMIN_IDS);
    }
}