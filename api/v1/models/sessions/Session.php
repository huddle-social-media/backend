<?php

namespace Models;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

use Exception;

class Session extends HuddleObj
{
    private $session_id = NULL;
    private $user_id;
    private $refresh_token;
    private $expiration_time;

    protected $dbTable = 'session'; // ++++++++++++++++++++++++++++++++++++++++++++
    protected $primaryKeysArray = ['session_id']; // +++++++++++++++++++++++++++++++++++++++++

    public function initialize($user_id, $refreshToken, $expirationTime)
    {
        $this->setUserId($user_id);
        $this->setRefreshToken($refreshToken);
        $this->setExpirationTime($expirationTime);
    }

    public function setUserId($user_id)
    {
        if(($user_id !== null) && (!is_numeric($user_id) || $user_id <= 0 || $user_id > 9223372036854775807 || $this->user_id !== null))
            throw new Exception("Can not set the user ID");
        $this->user_id = $user_id;
    }

    public function setSessionId($session_id)
    {
        if(($session_id !== null) && (!is_numeric($session_id) || $session_id <= 0 || $session_id > 9223372036854775807 || $this->session_id !== null))
            throw new Exception("Can not set the session ID");
        $this->session_id = $session_id;
    }

    public function setRefreshToken($refreshToken)
    {
        if(strlen($refreshToken) < 1 || strlen($refreshToken) > 255)
            throw new Exception("Can not set the refresh token");
        $this->refresh_token = $refreshToken;
    }

    public function setExpirationTime($expiration_time)
    {
        if(!is_numeric($expiration_time) || $expiration_time < 0)
            throw new Exception('Can not set the expiration time');
        $this->expiration_time = date('y/m/d H:i:s', $expiration_time);
    }

    public function getSessionId()
    {
        return $this->session_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    public function getExpirationTime()
    {
        return $this->expiration_time;
    }

    public function getExpirationTimeInUnix()
    {
        return strtotime($this->expiration_time);
    }

    public function isExpired()
    {
        if(time() >= $this->expiration_time)
            return true;
        return false;
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // public function create()
    // {
    //     $sessionRepo = new \Repository\SessionRepo();
    //     $sessionRepo->create($this);
    // }

    // public function update()
    // {
    //     $sessionRepo = new \Repository\SessionRepo();
    //     $sessionRepo->update($this);
    // }
}