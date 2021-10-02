<?php

class Authentication
{
    private const SECRET_KEY = "secret_key";
    private $rotateStatus = false;
    private $leeway = 0;

    public function issueRefreshToken()
    {

    }

    public function enableRotate()
    {
        $this->rotateStatus = true;
    }

    public function disableRotate()
    {
        $this->rotateStatus = false;
    }

    public function authenticate($request, $response)
    {
        
    }

}