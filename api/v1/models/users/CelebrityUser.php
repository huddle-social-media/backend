<?php

namespace Models;

use Exception;

require_once __DIR__."/User.php";
require_once __DIR__."/../../repository/usersRepo/CelebirtyUserRepo.php";

class CelebrityUser extends User
{
    private const statusCodes = ['active', 'deactive', 'locked', 'unverified'];
    private $firstname;
    private $lastname;
    private $dob;
    private $gender;

    public function __construct($firstname, $lastname, $username, $type, $email, $interest, $year, $month, $day, $gender, $password)
    {
        parent::__construct($username, $email, $interest, $password);
        $this->setFirstname($firstname);
        $this->setLastname($lastname);
        $this->setType($type);
        $this->setGender($gender);
        $this->setDob($year, $month, $day);
        $this->setStatus(self::statusCodes[3]);
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

    public function create()
    {
        // call the orm for creating the user
        $celebrityRepo = new \Repository\CelebrityUserRepo();
        return $celebrityRepo->create($this);
    }

}