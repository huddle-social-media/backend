<?php

namespace Models;

use Exception;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class OrganizationUser extends User
{
    protected const statusCodes = ['active', 'deactive', 'locked', 'unverified'];
    private $organization_name;
    private $doe;
    private $loc_lng;
    private $loc_lat;

    public static function create()
    {
        return new self();
    }

    public function initialize($organizationName, $username, $email, $interest, $year, $month, $day, $locLat, $locLng, $password, $status = self::statusCodes[3])
    {
        $this->setDoe($year, $month, $day);
        $this->setLocLat($locLat);
        $this->setLocLng($locLng);
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

    public function setLocLat($locLat)
    {
        if(strlen($locLat) < 1 || strlen($locLat) > 255)
            throw new Exception("Can not set the location");
        $this->loc_lat = $locLat;
    }

    public function setLocLng($locLng)
    {
        if(strlen($locLng) < 1 || strlen($locLng) > 255)
            throw new Exception("Can not set the location");
        $this->loc_lng = $locLng;
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

    public function getLocLat()
    {
        return $this->loc_lat;
    }

    public function getLocLng()
    {
        return $this->loc_lng;
    }

    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // public function create()
    // {
    //     // call the orm for creating the user
    //     $organizationRepo = new \Repository\OrganizationUserRepo();
    //     return $organizationRepo->create($this); 
    // }

}