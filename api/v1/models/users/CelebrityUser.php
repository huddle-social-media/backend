<?php

namespace Models;

use Exception;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class CelebrityUser extends User
{
    private const statusCodes = ['active', 'deactive', 'locked', 'unverified'];
    private $first_name;
    private $last_name;
    private $dob;
    private $gender;

    public static function create()
    {
        return new self();
    }

    public function initialize($first_name, $last_name, $username, $type, $email, $interest, $year, $month, $day, $gender, $password)
    {
        $this->setFirstname($first_name);
        $this->setLastname($last_name);
        $this->setType($type);
        $this->setGender($gender);
        $this->setDob($year, $month, $day);
        $this->setStatus(self::statusCodes[3]);
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setInterest($interest);
        $this->setPassword($password);
    }

    

    public function setFirstname($first_name)
    {
        if(strlen($first_name) < 1 || strlen($first_name) > 255)
            throw new Exception("Can not set the firstname");
        $this->first_name = $first_name;
    }

    public function setLastname($last_name)
    {
        if(strlen($last_name) < 1 || strlen($last_name) > 255)
            throw new Exception("Can not set the lastname");
        $this->last_name = $last_name;
    }

    public function setGender($gender)
    {
        if(($gender !== 'M' && $gender !== 'F' && $gender !== 'O') || strlen($gender) !== 1)
            throw new Exception("Can not set the gender");
        $this->gender = $gender;
    }

    public function setType($type)
    {
        if(strlen($type) < 1 || strlen($type) > 12 || $type !== 'celebrity')
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
        return $this->first_name;
    }

    public function getLastname()
    {
        return $this->last_name;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getDob()
    {
        return $this->dob;
    }

    // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // public function create()
    // {
    //     // call the orm for creating the user
    //     $celebrityRepo = new \Repository\CelebrityUserRepo();
    //     return $celebrityRepo->create($this);
    // }

}