<?php

namespace Controllers;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

use Exception;
use \Helpers\JWT as JWT;
use \Helpers\PasswordHandler as PasswordHandler;
use PDOException;


class OrganizationController extends UserController
{
    // @override
    public static function signUp($req, $res)
    {
        if(!($body = $req->body()))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Request body is empty or not valid");
            $res->send();
            exit;
        }

        if($req->contentType() !== "application/json")
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Content type invalid");
            $res->send();
            exit;
        }

        if(!isset($body->email) || !isset($body->username) || !isset($body->password) || !isset($body->type) || !isset($body->organizationName) || !isset($body->doe) || !isset($body->interest) || !isset($body->location))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Invalid request body1");
            (!isset($body->email)) ? $res->addMessage("Email not provided") : false ;
            (!isset($body->username)) ? $res->addMessage("Username not provided") : false ;
            (!isset($body->password)) ? $res->addMessage("Password not provided") : false ;
            (!isset($body->type)) ? $res->addMessage("Type not provided") : false ;
            (!isset($body->organizationName)) ? $res->addMessage("Organization name not provided") : false ;
            (!isset($body->doe)) ? $res->addMessage("DOE not provided") : false ;
            (!isset($body->interest)) ? $res->addMessage("Interest not provided") : false ;
            (!isset($body->location)) ? $res->addMessage("Location not provided") : false ;
            $res->send();
            exit;
        }

        if(strlen($body->email) < 1 || strlen($body->email) > 255 || strlen($body->password) < 1 || strlen($body->password) > 255 || strlen($body->password) > 255 || strlen($body->username) < 1 || strlen($body->username) > 255 || strlen($body->interest) < 1 || strlen($body->interest) > 255 || strlen($body->type) < 1 || strlen($body->type) > 12  || strlen($body->organizationName) < 1 || strlen($body->organizationName) > 255 || strlen($body->location->locLng) < 1 || strlen($body->location->locLng) > 255 || strlen($body->location->locLat) < 1 || strlen($body->location->locLat) > 255 || !is_numeric($body->doe->year) || !is_numeric($body->doe->month) || !is_numeric($body->doe->day))
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
            (strlen($body->organizationName) < 1) ? $res->addMessage("Organization name can not be blank") : false ;
            (strlen($body->organizationName) > 255) ? $res->addMessage("Organization name cannot be greater than 255 characters") : false ;
            (strlen($body->location->locLng) < 1) ? $res->addMessage("Longitude can not be blank") : false ;
            (strlen($body->location->locLat) < 1) ? $res->addMessage("Latitude can not be blank") : false ;
            (strlen($body->location->locLng) > 255) ? $res->addMessage("Longitude cannot be greater than 255 characters") : false ;
            (strlen($body->location->locLat) > 255) ? $res->addMessage("Latitude cannot be greater than 255 characters") : false ;
            (!is_numeric($body->doe->year)) ? $res->addMessage("Year should be numeric") : false ;
            (!is_numeric($body->doe->month)) ? $res->addMessage("Month should be numeric") : false ;
            (!is_numeric($body->doe->day)) ? $res->addMessage("day should be numeric") : false ;
            $res->send();
            exit;
        }


        if($body->doe->year > date('Y') || $body->doe->month < 1 || $body->doe->month > 12 || $body->doe->day < 1 || (cal_days_in_month(CAL_GREGORIAN, $body->doe->month, $body->doe->year) < $body->doe->day))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Invalid date of establish");
            $res->send();
            exit;
        }

        if($body->type !== 'organization')
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Invalid request body4");
            ($body->type !== 'organization') ? $res->addMessage("Invalid type") : false ;
            $res->send();
            exit;
        }

        try{
            //$userRepo = new \Repository\UserRepo(); +++++++++++++++++++
            if(\Repository\ORM\ORM::checkUsername($body->username))
            {
                $res->setSuccess(false);
                $res->setHttpStatusCode(400);
                $res->addMessage("Invalid request body");
                $res->addMessage("Username exists");
                $res->send();
                exit;
            }
        }catch(PDOException $ex)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Invalid request body6");
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }

        try{
            //$userRepo = new \Repository\UserRepo(); ++++++++++++++++++++++++++++++++++++
            if(\Repository\ORM\ORM::checkEmail($body->email))
            {
                $res->setSuccess(false);
                $res->setHttpStatusCode(400);
                $res->addMessage("Invalid request body7");
                $res->addMessage("Email exists");
                $res->send();
                exit;
            }
        }catch(PDOException $ex)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Invalid request body8");
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }

        if(!\Helpers\PasswordHandler::verifyStrength($body->password))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Password is too weak");
            $res->send();
            exit;
        }


        $organizationName = trim($body->organizationName);
        $username = trim($body->username);
        // need to verify the email before use
        $email = trim($body->email);
        $interest = trim($body->interest);
        $locLat = trim($body->location->locLat);
        $locLng = trim($body->location->locLng);
        $year = $body->doe->year;
        $month = $body->doe->month;
        $day = $body->doe->day;
        $password = \Helpers\PasswordHandler::bcrypt($body->password);

        try{
            // $organizationUser = new \Models\OrganizationUser($organizationName, $username, $type, $email, $interest, $year, $month, $day, $location, $password);++++++++++++++++++++++
            $organizationUser = \Models\OrganizationUser::create();
            $organizationUser->initialize($organizationName, $username, $email, $interest, $year, $month, $day, $locLat, $locLng, $password);
            $id = \Repository\ORM\ORM::writeObject($organizationUser);
            $organizationUser->setUserId($id);
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->addMessage("User created successfully");
            $dataArray = \Repository\ORM\DatabaseObject::objectToArray($organizationUser, []);
            unset($dataArray["password"]);
            unset($dataArray["dbTable"]);
            unset($dataArray["primaryKeysArray"]);
            $res->setData($dataArray);
            $res->send();
            exit;
        }catch(Exception $ex){
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }
    }
}
