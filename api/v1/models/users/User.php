<?php

namespace Models;

use Exception;

require_once __DIR__."/../../helpers/PasswordHandler/PasswordHandler.php";

use \Helpers\PasswordHandler as PasswordHandler;

abstract class User
{
    private $userId;
    private $username;
    private $email;
    private $interest;
    private $banned = false;
    private $loginAttempts = 0;
    protected $status;
    protected $type;
    protected $hashedPassword;


    public function __construct($username, $email, $interest, $password)
    {
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setInterest($interest);
        $this->setPassword($password);
    }

    public function setUserId($userId)
    {
        if(($userId !== null) && (!is_numeric($userId) || $userId <= 0 || $userId > 9223372036854775807 || $this->userId !== null))
            throw new Exception("Can not set the user ID");
        $this->userId = $userId;
    }

    public function setUsername($username)
    {
        // need to implement the logic
        // for now this is the temp bofy
        $this->username = $username;
    }

    public function setPassword($password)
    {
        if(!PasswordHandler::verifyStrength($password))
            throw new Exception("Password is too weak");
        $this->hashedPassword = PasswordHandler::bcrypt($password);
    }

    public function setEmail($email)
    {
        // need to verify the email before use
        if(strlen($email) < 1 || strlen($email) > 255)
            throw new Exception("Can not set the email");
        $this->email = $email;
    }
    
    public function setInterest($interest)
    {
        if(strlen($interest) < 1 || strlen($interest) > 255)
        throw new Exception("Can not set the interest");
        $this->interest = $interest;
    }

    public function setBanned()
    {
        $this->banned = !$this->banned;
    }

    public function setLoginAttempts()
    {
        // need to implement
    }

    abstract public function setType($type);
    abstract public function setStatus($status);
    abstract public function addUser();
}