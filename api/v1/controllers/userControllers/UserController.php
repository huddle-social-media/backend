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
            $res->addMessage("Invalid request body");
            (strlen($body->email) < 1) ? $res->addMessage("Email can not be blank") : false;
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

        try{
            // $userRepo = new \Repository\UserRepo(); ++++++++++++++++++++++++++++
            if(\Repository\ORM\ORM::checkUsername($body->username))
            {
                $res->setSuccess(false);
                $res->setHttpStatusCode(400);
                $res->addMessage("Invalid request body5");
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
            // $userRepo = new \Repository\UserRepo(); +++++++++++++++++++++++++++++++++++++
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
        $password = PasswordHandler::bcrypt($body->password);

        // call the orm UserRepository
        if($type === 'casual')
        {
            try{
                // +++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // $casualUser = new \Models\CasualUser($firstname, $lastname, $username, $type, $email, $interest, $year, $month, $day, $gender, $password);
                // $casualUser = $casualUser->create();

                $casualUser = \Models\CasualUser::create();
                $casualUser->initialize($firstname, $lastname, $username, $type, $email, $interest, $year, $month, $day, $gender, $password);
                $res->setSuccess(true);
                $res->setHttpStatusCode(200);
                $res->addMessage("User created successfully");
                $res->setData(["id" => $casualUser->getUserId(),"firstname" => $casualUser->getFirstname(), "lastname" => $casualUser->getLastname(), "username" => $casualUser->getUsername(), "email" => $casualUser->getEmail(), "gender" => $casualUser->getGender(), "type" => $casualUser->getType(), "dob" => $casualUser->getDob(), "interest" => $casualUser->getInterest(), "status" => $casualUser->getStatus()]);
                $res->send();
                exit;

            }catch(Exception $ex){
                $res->setSuccess(false);
                $res->setHttpStatusCode(400);
                $res->addMessage($ex->getMessage());
                $res->send();
                exit;
            }
        }else if($type === 'celebrity')
        {
            try{
                // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                // $celebrityUser = new \Models\CelebrityUser($firstname, $lastname, $username, $type, $email, $interest, $year, $month, $day, $gender, $password);
                // $celebrityUser = $celebrityUser->create();
                $celebrityUser = \Models\CelebrityUser::create();
                $celebrityUser->initialize($firstname, $lastname, $username, $type, $email, $interest, $year, $month, $day, $gender, $password);
                $res->setSuccess(true);
                $res->setHttpStatusCode(200);
                $res->addMessage("User created successfully");
                $res->setData(["id" => $celebrityUser->getUserId(),"firstname" => $celebrityUser->getFirstname(), "lastname" => $celebrityUser->getLastname(), "username" => $celebrityUser->getUsername(), "email" => $celebrityUser->getEmail(), "gender" => $celebrityUser->getGender(), "type" => $celebrityUser->getType(), "dob" => $celebrityUser->getDob(), "interest" => $celebrityUser->getInterest(), "status" => $celebrityUser->getStatus()]);
                $res->send();
                exit;
            }catch(Exception $ex) {
                $res->setSuccess(false);
                $res->setHttpStatusCode(400);
                $res->addMessage($ex->getMessage());
                $res->send();
                exit;
            }
        }
    }

    public static function signIn($req, $res)
    {
        // simply  check the credentials and gives access
        
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
        
        if(!isset($body->email) || !isset($body->password))
        {
          $res->setSuccess(false);
          $res->setHttpStatusCode(400);
          (!isset($body->email)) ? $res->addMessage("Email not provided") : false;
          (!isset($body->password)) ? $res->addMessage("Password not provided") : false;
          $res->send();
          exit;
        }

        if(strlen($body->email) < 1 || strlen($body->email) > 255 || strlen($body->password) < 1 || strlen($body->password) > 255)
        {
          $res->setSuccess(false);
          $res->setHttpStatusCode(400);
          (strlen($body->email) < 1) ? $res->addMessage("Email can not be blank") : false;
          (strlen($body->email) > 255) ? $res->addMessage("Email cannot be greater than 255 characters") : false;
          (strlen($body->password) < 1) ? $res->addMessage("Password can not be blank") : false;
          (strlen($body->password) > 255) ? $res->addMessage("Password cannot be greater than 255 characters") : false;
          $res->send();
          exit;
        }

        try{
            // $userRepo = new \Repository\UserRepo(); ++++++++++++++++++++++++++++++
            if(!(\Repository\ORM\ORM::checkEmail($body->email)))
            {
                $res->setSuccess(false);
                $res->setHttpStatusCode(400);
                $res->addMessage("Invalid email or password");
                $res->send();
                exit;
            }

            $email = $body->email;
            $password = $body->password;
            // $user = $userRepo->getUser($email); +++++++++++++++++++++++++
            $user = \Repository\ORM\ORM::makeUserByEmail($email, 'User');
            

            if($user->getBanned() === true)
            {
                // redirected to banned page
                $res->setSuccess(false);
                $res->setHttpStatusCode(401);
                $res->addMessage("User banned from the platform");
                $res->send();
                exit;
            }

            if($user->getStatus() === 'locked')
            {
                // redirect to locked page
                $res->setSuccess(false);
                $res->setHttpStatusCode(401);
                $res->addMessage("User locked from the platform");
                $res->send();
                exit;
            }

            if($user->getStatus() === 'unverified')
            {
                // redirect to unverified page
                $res->setSuccess(false);
                $res->setHttpStatusCode(401);
                $res->addMessage("User unverified");
                $res->send();
                exit;
            }

            // if($user->loginAttempts >= 5)
            // {
            //     // redirect to locked page
            //     $res->setSuccess(false);
            //     $res->setHttpStatusCode(401);
            //     $res->addMessage("Account locked due to login attempts");
            //     $res->send();
            //     exit;
            // }

            if(!password_verify($password, $user->getPassword()))
            {
                // redirect to login failed
                $res->setSuccess(false);
                $res->setHttpStatusCode(401);
                $res->addMessage("Email or password incorrect");
                $res->send();
                exit;
            }

            $refreshExp = time()+30*86400;
            $accessExp = time()+30;

            $payload = [
                "userId" => $user->getUserId(),
                "exp" => $accessExp,
                "aud" => $user->getType()
            ];

            $authorizationService = new \Services\Authorization();
            $accessToken = JWT::encode($payload, $authorizationService->getSecretKey(), 'HS256');
            $refreshToken = base64_encode(bin2hex(openssl_random_pseudo_bytes(32).time()));
            // $session = new \Models\Session($user->userId, $refreshToken, $refreshExp); +++++++++++++++++++++++++++
            $session = \Models\Session::create();
            $session->initialize($user->getUserId(), $refreshToken, $refreshExp);       
            \Repository\ORM\ORM::writeObject($session);
            

            $arr_cookie_options = array (
                'expires' => time() +30*86400,
                'path' => '/',
                'domain' => null, // leading dot for compatibility or use subdomain
                'secure' => true,     // or false
                'httponly' => true,    // or false
                'samesite' => 'None' // None || Lax  || Strict
                );
            
            setcookie('refreshToken', $refreshToken, $arr_cookie_options);
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->addMessage("Login succes");
            $userArray = \Repository\ORM\DatabaseObject::objectToArray($user, []);
            unset($userArray['password']);
            
            $res->setData(["accessToken" => $accessToken, "user" => $userArray]);
            $res->send();
            exit;
        }catch(PDOException $ex) {
            $res->setSuccess(false);
            $res->setHttpStatusCode(500);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }

    }


    // helper route for checking the validity of the username
    public static function checkUsername($req, $res)
    {
        if($body = $req->body())
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
            
            if(\Repository\ORM\ORM::checkUsername($username))
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

    public static function sayHello($req, $res)
    {
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->addMessage("Successfully authenticated");
        $res->send();
        exit;
    }

    public static function CROS($req, $res) {
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Max-Age: 5184000');
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->send();
        exit;
    }
}
