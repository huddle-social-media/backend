<?php

namespace Models;

use Exception;

class Session
{
    private $sessionId;
    private $userId;
    private $refreshToken;
    private $exp;

    public function __construct($userId, $refreshToken, $exp)
    {
        
    }

    public function setUserId($userId)
    {
        if(($userId !== null) && (!is_numeric($userId) || $userId <= 0 || $userId > 9223372036854775807 || $this->userId !== null))
            throw new Exception("Can not set the user ID");
        $this->userId = $userId;
    }

    public function setRefreshToken($refreshToken)
    {
        if(strlen($refreshToken) < 1 || strlen($refreshToken) > 255)
            throw new Exception("Can not set the refresh token");
        $this->refreshToken = $refreshToken;
    }

    public function setExpirationTime($expirationTime)
    {
        if(!is_numeric($expirationTime))
        {
            // throw new exception
        }
    }
}