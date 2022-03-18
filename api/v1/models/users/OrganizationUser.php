<?php

namespace Models;

use Exception;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class OrganizationUser extends User
{
    protected const statusCodes = ['active', 'deactive', 'locked', 'unverified'];
    private $organization_name;
    private $doe;
    private $location;

    public static function create()
    {
        return new self();
    }

    public function initialize($organizationName, $username, $email, $interest, $year, $month, $day, $location, $password, $status = self::statusCodes[3])
    {
        $this->setDoe($year, $month, $day);
        $this->setLocation($location);
        $this->setOrganizationName($organizationName);
        $this->setType("organization");
        $this->setStatus($status);
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setInterest($interest);
        $this->setPassword($password);

    }

    public function setOrganizationName($organizationName)
    {
        if(strlen($organizationName) < 1 || strlen($organizationName) > 255)
            throw new Exception("Can not set the organization name");
        $this->organization_name = $organizationName;
    }

    public function setLocation($location)
    {
        if(strlen($location) < 1 || strlen($location) > 255)
            throw new Exception("Can not set the location");
        $this->location = $location;
    }

    public function setGender($gender)
    {
        if(($gender !== 'M' && $gender !== 'F' && $gender !== 'O') || strlen($gender) !== 1)
            throw new Exception("Can not set the gender");
        $this->gender = $gender;
    }

    public function setType($type)
    {
        if(strlen($type) < 1 || strlen($type) > 12 || $type !== 'organization')
            throw new Exception("Can not set the type");
        $this->type = $type;
    }

    public function setStatus($status)
    {
        if(!in_array($status, self::statusCodes, true))
            throw new Exception("Can not set the status");
        $this->status = $status;
    }

    public function setDoe($year, $month, $day)
    {
        if(!is_numeric($year) || !is_numeric($month) || !is_numeric($day) || $year > date('Y') || $month < 1 || $month > 12 || $day < 1 || (cal_days_in_month(CAL_GREGORIAN, $month, $year) < $day))
            throw new Exception("Can not set the date of birth");
        $date = date_create($year."-".$month."-".$day);
        $this->doe = date_format($date,"y/m/d");
    }

    public function getOrganizationName()
    {
        return $this->organization_name;
    }

    public function getDoe()
    {
        return $this->doe;
    }

    public function getLocation()
    {
        return $this->location;
    }

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // public function create()
    // {
    //     // call the orm for creating the user
    //     $organizationRepo = new \Repository\OrganizationUserRepo();
    //     return $organizationRepo->create($this); 
    // }

}