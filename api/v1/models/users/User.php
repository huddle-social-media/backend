<?php

namespace Models;

use Exception;

require_once __DIR__."/../../helpers/PasswordHandler/PasswordHandler.php";

use \Helpers\PasswordHandler as PasswordHandler;

abstract class User
{
    private $userId = null;
    private $username;
    private $email;
    private $interest;
    private $banned = false;
    private $loginAttempts = 0;
    private $loggedIn = false;
    protected $status;
    protected $type;
    protected $password;


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
        if(strlen($password) < 1 || strlen($password) > 255)
            throw new Exception("Can not set the password");
        $this->password = $password;
    }

    public function setEmail($email)
    {
        // need to verify the email before use
        if(strlen($email) < 1 || strlen($email) > 255)
            throw new Exception("Can not set the email");
        $this->email = $email;
    }

    public function setLoggedIn($loginStatus)
    {
        if(!is_bool($loginStatus))
            throw new Exception("Can not change the login status");
        $this->loggedIn = $loginStatus;
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

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getInterest()
    {
        return $this->interest;
    }

    public function getLoggedIn()
    {
        return $this->loggedIn;
    }

    public function getLoginAttempts()
    {
        return $this->loginAttempts;
    }

    public function getHashedPassword()
    {
        return $this->password;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getType()
    {
        return $this->type;
    }

    abstract public function setType($type);
    abstract public function setStatus($status);
    abstract public function create();
}
