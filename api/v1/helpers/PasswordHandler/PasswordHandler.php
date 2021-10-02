<?php

namespace Helpers;

class PasswordHandler
{
    public static function verifyStrength($password)
    {
        $regExp = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';
        return preg_match($regExp, $password);
    }

    public static function bcrypt($password)
    {
        if(!self::verifyStrength($password))
            return false;
        return password_hash($password, PASSWORD_DEFAULT); 
    }
}