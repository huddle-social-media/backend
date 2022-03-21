<?php

namespace Models;

use Exception;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class EventAttendant extends HuddleObj
{
    private $event_id;
    private $user_id;
    private $status;

    protected $dbTable = "event_attendant";
    protected $primaryKeysArray = ["event_id", "user_id"];

    public function initialize($eventId, $userId, $status='active')
    {
        $this->setEventId($eventId);
        $this->setUserId($userId);
        $this->setStatus($status);
    }


    public function setEventId($eventId)
    {
        $this->event_id = $eventId;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    public function setStatus($status)
    {
        if($status != "active" && $status != "deactive")
        {
            throw new Exception("Status has to be either active or deactive");
            exit;
        }

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

    public function getStatus()
    {
        return $this->status;
    }
}