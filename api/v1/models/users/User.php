<?php

namespace Models;

use Exception;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

use \Helpers\PasswordHandler as PasswordHandler;

class User extends HuddleObj
{
    private $user_id = null;
    private $username;
    private $email;
    private $interest;
    private $banned = false;
    private $login_attempts = 0;
    private $logged_in = false;
    protected $status;
    protected $type;
    protected $password;

    
    protected $dbTable = 'user';
    protected $primaryKeysArray = ['user_id'];

    
    public function setUserId($user_id)
    {
        if(($user_id !== null) && (!is_numeric($user_id) || $user_id <= 0 || $user_id > 9223372036854775807 || $this->user_id !== null))
            throw new Exception("Can not set the user ID");
        $this->user_id = $user_id;
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

    public function unsetPassword()
    {
        $this->password = null;
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
        $this->logged_in = $loginStatus;
    }

    public function setInterest($interest)
    {
        if(strlen($interest) < 1 || strlen($interest) > 255)
            throw new Exception("Can not set the interest");
        $this->interest = $interest;
    }

    public function setBanned($status)
    {
        $this->banned = $status;
    }

    public function getBannedStatus()
    {
        return $this->banned;
    }

    public function setLoginAttempts()
    {
        // need to implement
    }

    public function getUserId()
    {
        return $this->user_id;
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
        return $this->logged_in;
    }

    public function getLoginAttempts()
    {
        return $this->login_attempts;
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
}
