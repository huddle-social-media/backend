<?php

namespace Models;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

use Exception;

class Session
{
    private $sessionId;
    private $userId;
    private $refreshToken;
    private $expirationTime;

   
    public static function create()
    {
        return new self();
    }

    public function initialize($userId, $refreshToken, $expirationTime)
    {
        $this->setUserId($userId);
        $this->setRefreshToken($refreshToken);
        $this->setExpirationTime($expirationTime);
    }

    public function setUserId($userId)
    {
        if(($userId !== null) && (!is_numeric($userId) || $userId <= 0 || $userId > 9223372036854775807 || $this->userId !== null))
            throw new Exception("Can not set the user ID");
        $this->userId = $userId;
    }

    public function setSessionId($sessionId)
    {
        if(($sessionId !== null) && (!is_numeric($sessionId) || $sessionId <= 0 || $sessionId > 9223372036854775807 || $this->sessionId !== null))
            throw new Exception("Can not set the session ID");
        $this->sessionId = $sessionId;
    }

    public function setRefreshToken($refreshToken)
    {
        if(strlen($refreshToken) < 1 || strlen($refreshToken) > 255)
            throw new Exception("Can not set the refresh token");
        $this->refreshToken = $refreshToken;
    }

    public function setExpirationTime($expirationTime)
    {
        if(!is_numeric($expirationTime) || $expirationTime < 0)
            throw new Exception('Can not set the expiration time');
        $this->expirationTime = $expirationTime;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function getExpirationTime()
    {
        return $this->expirationTime;
    }

    public function isExpired()
    {
        if(time() >= $this->expirationTime)
            return true;
        return false;
    }
}