<?php

namespace Controllers;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";


use Exception;
use \Helpers\JWT as JWT;
use \Helpers\PasswordHandler as PasswordHandler;
use PDOException;

abstract class UserController 
{
    public static function signUp($req, $res)
    {   
        $body = $req->validateRequest($res);

        if(!isset($body->email) || !isset($body->username) || !isset($body->password) || !isset($body->type) || !isset($body->firstname) || !isset($body->lastname) || !isset($body->gender) || !isset($body->dob) || !isset($body->interest))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Invalid request body1");
            (!isset($body->email)) ? $res->addMessage("Email not provided") : false ;
            (!isset($body->username)) ? $res->addMessage("Username not provided") : false ;
            (!isset($body->password)) ? $res->addMessage("Password not provided") : false ;
            (!isset($body->type)) ? $res->addMessage("Type not provided") : false ;
            (!isset($body->firstname)) ? $res->addMessage("Firstname not provided") : false ;
            (!isset($body->lastname)) ? $res->addMessage("Lastname not provided") : false ;
            (!isset($body->gender)) ? $res->addMessage("Gender not provided") : false ;
            (!isset($body->dob)) ? $res->addMessage("DOB not provided") : false ;
            (!isset($body->interest)) ? $res->addMessage("Interest not provided") : false ;
            $res->send();
            exit;
        }

        if(strlen($body->email) < 1 || strlen($body->email) > 255 || strlen($body->password) < 1 || strlen($body->password) > 255 || strlen($body->password) > 255 || strlen($body->username) < 1 || strlen($body->username) > 255 || strlen($body->interest) < 1 || strlen($body->interest) > 255 || strlen($body->type) < 1 || strlen($body->type) > 12 || strlen($body->gender) !== 1 || strlen($body->firstname) < 1 || strlen($body->firstname) > 255 || strlen($body->lastname) < 1 || strlen($body->lastname) > 255 || !is_numeric($body->dob->year) || !is_numeric($body->dob->month) || !is_numeric($body->dob->day))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Invalid request body2");
            (strlen($body->email) < 1) ? $res->addMessage("Email can not be blank") : false ;
            (strlen($body->email) > 255) ? $res->addMessage("Email cannot be greater than 255 characters") : false ;
            (strlen($body->password) < 1) ? $res->addMessage("Password can not be blank") : false ;
            (strlen($body->password) > 255) ? $res->addMessage("Password cannot be greater than 255 characters") : false ;
            (strlen($body->username) < 1) ? $res->addMessage("Username can not be blank") : false ;
            (strlen($body->username) > 255) ? $res->addMessage("Username cannot be greater than 255 characters") : false ;
            (strlen($body->interest) < 1) ? $res->addMessage("Interest can not be blank") : false ;
            (strlen($body->interest) > 255) ? $res->addMessage("Username cannot be greater than 255 characters") : false ;
            (strlen($body->type) < 1) ? $res->addMessage("Type can not be blank") : false ;
            (strlen($body->type) > 12) ? $res->addMessage("Type cannot be greater than 12 characters") : false ;
            (strlen($body->firstname) < 1) ? $res->addMessage("First name can not be blank") : false ;
            (strlen($body->firstname) > 255) ? $res->addMessage("First name cannot be greater than 255 characters") : false ;
            (strlen($body->lastname) < 1) ? $res->addMessage("Last name can not be blank") : false ;
            (strlen($body->lastname) > 255) ? $res->addMessage("Last name cannot be greater than 255 characters") : false ;
            (strlen($body->gender) !== 1) ? $res->addMessage("Gender cannot be greater than 1 characters") : false ;
            (!is_numeric($body->dob->year)) ? $res->addMessage("Year should be numeric") : false ;
            (!is_numeric($body->dob->month)) ? $res->addMessage("Month should be numeric") : false ;
            (!is_numeric($body->dob->day)) ? $res->addMessage("day should be numeric") : false ;
            $res->send();
            exit;
        }


        if($body->dob->year < date('Y')-120 || $body->dob->year > date('Y') || $body->dob->month < 1 || $body->dob->month > 12 || $body->dob->day < 1 || (cal_days_in_month(CAL_GREGORIAN, $body->dob->month, $body->dob->year) < $body->dob->day))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Invalid date of birth");
            $res->send();
            exit;
        }

        if(($body->type !== 'celebrity' && $body->type !== 'casual') || ($body->gender !== 'M' && $body->gender !== 'F' && $body->gender !== 'O'))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Invalid request body4");
            ($body->type !== 'celebrity' && $body->type !== 'casual') ? $res->addMessage("Invalid type") : false ;
            ($body->gender !== 'M' && $body->gender !== 'F' && $body->gender !== 'O') ? $res->addMessage("Invalid gender") : false;
            $res->send();
            exit;
        }

        if(!PasswordHandler::verifyStrength($body->password))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Password is too weak");
            $res->send();
            exit;
        }
        

        $firstname = trim($body->firstname);
        $lastname = trim($body->lastname);
        $username = trim($body->username);
        // need to verify the email before use
        $email = trim($body->email);
        $interest = trim($body->interest);
        $gender = $body->gender;
        $type = $body->type;
        $year = $body->dob->year;
        $month = $body->dob->month;
        $day = $body->dob->day;
        $password = $body->password;

        // call the orm UserRepository
        // if($type === 'casual')
        // {
        //     try{
        //         $casualUser = new \Models\CasualUser($firstname, $lastname, $username, $type, $email, $interest, $year, $month, $day, $gender, $password);
        //         var_dump($casualUser);
        //     }catch(Exception $ex){
        //         $res->setSuccess(false);
        //         $res->setHttpStatusCode(400);
        //         $res->addMessage($ex->getMessage());
        //         $res->send();
        //         exit;
        //     }
        // }else if($type === 'celebrity')
        // {
        //     try{
        //         $celebrityUser = new \Models\CelebrityUser($firstname, $lastname, $username, $type, $email, $interest, $year, $month, $day, $gender, $password);
        //         var_dump($celebrityUser);
        //     }catch(Exception $ex) {
        //         $res->setSuccess(false);
        //         $res->setHttpStatusCode(400);
        //         $res->addMessage($ex->getMessage());
        //         $res->send();
        //         exit;
        //     }
        // }
    }   

    public static function login($req, $res)
    {

    }

    public static function checkUsername($req, $res)
    {
     
        if($req->contentType() !== "application/json")
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Content type invalid");
            $res->send();
            exit;
        }

        if(($body = $req->body()) === false)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Request body contain invalid JSON");
            $res->send();
            exit;
        }

        if(!isset($body->username))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Username not provided");
            $res->send();
            exit;
        }

        if(strlen($body->username) < 1 || strlen($body->username) > 255)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            (strlen($body->username) < 1) ? $res->addMessage("Username can not be blank") : false ;
            (strlen($body->username) > 255) ? $res->addMessage("Username cannot be greater than 255 characters") : false ;
            $res->send();
        }

        $username = $body->username;
        try{
            
            if(\Repository\ORM\ORM::checkUsername($body->username))
            {
                $res->setSuccess(true);
                $res->setHttpStatusCode(200);
                $res->addMessage("Username exists");
                $res->setData(["exists" => true]);
                $res->send();
                exit;
            }
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->addMessage("Username not exists");
            $res->setData(["exists" => false]);
            $res->send();
            exit;

        }catch(\PDOException $ex)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(500);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }
    }
}