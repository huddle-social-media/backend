<?php

class UsernameExistException extends DomainException
{
    public function getStatusCode()
    {
        return 409;
    }
}