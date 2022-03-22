<?php

namespace Models;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class Event extends HuddleObj
{

    private $event_id;
    private $user_id;
    private $title;
    private $description;
    private $event_date;
    private $event_time;
    private $loc_lat;
    private $loc_lng;
    private $interest;
    private $going;
    private $status;

    protected $dbTable = "event";
    protected $primaryKeysArray = ["event_id"];

    public function initialize($userId, $title, $description, $eventDate, $eventTime, $locLat, $locLng, $interest, $going, $status = 'active')
    {
        $this->setUserId($userId);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setEventDate($eventDate);
        $this->setEventTime($eventTime);
        $this->setLocLat($locLat);
        $this->setLocLng($locLng);
        $this->setInterest($interest);
        $this->setStatus($status);
        $this->setGoing($going);

    }

    public function setEventId($eventId)
    {
        $this->event_id = $eventId;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setEventDate($eventDate)
    {
        $this->event_date = $eventDate;
    }

    public function setEventTime($eventTime)
    {
        $this->event_time = $eventTime;
    }

    public function setLocLat($locLat)
    {
        $this->loc_lat = $locLat;
    }

    public function setLocLng($locLng)
    {
        $this->loc_lng = $locLng;
    }

    public function setInterest($interest)
    {
        $this->interest = $interest;
    }

    public function setGoing($going)
    {
        $this->going = $going;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getEventId()
    {
        return $this->event_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getEventDate()
    {
        return $this->event_date;
    }

    public function getEventTime()
    {
        return $this->event_time;
    }

    public function getLocLat()
    {
        return $this->loc_lat;
    }

    public function getLocLng()
    {
        return $this->loc_lng;
    }

    public function getInterest()
    {
        return $this->interest;
    }

    public function getGoing()
    {
        return $this->going;
    }

    public function getStatus()
    {
        return $this->status;
    }
}