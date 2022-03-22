<?php

namespace Models;

class Issue extends HuddleObj
{
    private $issue_id;
    private $user_id;
    private $state;
    private $title;
    private $description;
    private $interest;
    private $embedded_media;
    private $media_count;
    private $issue_date;
    private $issue_time;
    private $status;

    protected $dbTable = "issue";
    protected $primaryKeysArray = ['issue_id'];
    

    public function initialize($userId, $state, $title, $description, $interest, $embeddedMedia, $mediaCount, $status)
    {
        
        $this->setUserId($userId);
        $this->setState($state);
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setInterest($interest);
        $this->setEmbeddedmedia($embeddedMedia);
        $this->setMediaCount($mediaCount);
        $this->setStatus($status);
    }

    

    public function setIssueId($issueId)
    {
        $this->issue_id = $issueId;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setInterest($interest)
    {
        $this->interest = $interest;
    }

    public function setEmbeddedmedia($embeddedMedia)
    {
        $this->embedded_media = $embeddedMedia;
    }

    public function setMediaCount($mediaCount)
    {
        $this->media_count = $mediaCount;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setIssueDate($date)
    {
        $this->issue_date = $date;
    }

    public function setIssueTime($time)
    {
        $this->issue_time = $time;
    }

    public function getIssueId()
    {
        return $this->issue_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getInterest()
    {
        return $this->interest;
    }

    public function getEmbeddedmedia()
    {
        return $this->embedded_media;
    }

    public function getMediaCount()
    {
        return $this->media_count;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getIssueDate()
    {
        return $this->issue_date;
    }

    public function getIssueTime()
    {
        return $this->issue_time;
    }


    
}
