<?php

namespace Models;

class IssueAccepted extends HuddleObj
{
    private $issue_id;
    private $accepted_user;
    private $chat_status;
    private $date_time;
    private $status;
    private $accepted_date;
    private $accepted_time;
    private $state;

    protected $dbTable = 'issue_accepted';
    protected $primaryKeysArray = ['issue_id', 'accepted_user'];

    public function initialize($issueId, $acceptedUser, $chatStatus, $acceptedDate, $acceptedTime, $state = 'open')
    {
        $this->setIssueId($issueId);
        $this->setAcceptedUser($acceptedUser);
        $this->setChatStatus($chatStatus);
        $this->setAcceptedDate($acceptedDate);
        $this->setAcceptedTime($acceptedTime);
        $this->setState($state);
    }

    public function setIssueId($issueId)
    {
        $this->issue_id = $issueId;
    }

    public function setAcceptedUser($acceptedUser)
    {
        $this->accepted_user =$acceptedUser;
    }

    public function setChatStatus($chatStatus)
    {
        $this->chat_status = $chatStatus;
    }

    public function setDateTime($dateTime)
    {
        $this->date_time = $dateTime;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setAcceptedDate($date)
    {
        $this->accepted_date = $date;
    }

    public function setAcceptedTime($time)
    {
        $this->accepted_time = $time;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getIssueId()
    {
        return $this->issue_id;
    }

    public function getAcceptedUser()
    {
        return $this->accepted_user;
    }

    public function getChatStatus()
    {
        return $this->chat_status;
    }

    public function getDateTime()
    {
        return $this->date_time;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getAcceptedDate()
    {
        return $this->accepted_date;
    }

    public function getAcceptedTime()
    {
        return $this->accepted_time;
    }

    public function getState()
    {
        return $this->state;
    }
}