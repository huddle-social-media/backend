<?php

namespace Models;


require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

use \Exception;

class CasualUser extends User
{
    private const statusCodes = ['active', 'deactive', 'locked'];
    private $firstname;
    private $lastname;
    private $dob;
    private $gender;

    public static function create()
    {
        return new self();
    }

    public function initialize($firstname, $lastname, $username, $type, $email, $interest, $year, $month, $day, $gender, $password)
    {
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setGender($gender);
        $this->setType($type);
        $this->setDob($year, $month, $day);
        $this->setStatus('active');
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setInterest($interest);
        $this->setPassword($password);
    }

    public function setFirstname($firstname)
    {
        if(strlen($firstname) < 1 || strlen($firstname) > 255)
            throw new Exception("Can not set the firstname");
        $this->firstname = $firstname;
    }

    public function setLastname($lastname)
    {
        if(strlen($lastname) < 1 || strlen($lastname) > 255)
            throw new Exception("Can not set the lastname");
        $this->lastname = $lastname;
    }

    public function setGender($gender)
    {
        if(($gender !== 'M' && $gender !== 'F' && $gender !== 'O') || strlen($gender) !== 1)
            throw new Exception("Can not set the gender");
        $this->gender = $gender;
    }

    public function setType($type)
    {
        if(strlen($type) < 1 || strlen($type) > 12 || $type !== 'casual')
            throw new Exception("Can not set the type");
        $this->type = $type;
    }

    public function setDob($year, $month, $day)
    {
        if(!is_numeric($year) || !is_numeric($month) || !is_numeric($day) || $year < date('Y')-120 || $year > date('Y') || $month < 1 || $month > 12 || $day < 1 || (cal_days_in_month(CAL_GREGORIAN, $month, $year) < $day))
            throw new Exception("Can not set the date of birth");
        $date = date_create($year."-".$month."-".$day);
        $this->dob = date_format($date,"y/m/d");
    }

    public function setStatus($status)
    {
        if(!in_array($status, self::statusCodes, true))
            throw new Exception("Can not set the status");
        $this->status = $status;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getDob()
    {
        return $this->dob;
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // public function create()
    // {
    //     // call the orm for creating the user
    //     $casualRepo = new \Repository\CasualUserRepo();
    //     return $casualRepo->create($this);
    // }

}
