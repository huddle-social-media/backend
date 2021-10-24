<?php
namespace Models;
use Models\HuddleObj;

class Tip extends HuddleObj
{
    private $tip_id;
    private $user_id;
    private $description;
    private $hashtags;
    private $date_time;
    private $interest;
    protected $dbTable = 'Tip';
    protected $primaryKeysArray = ['tip_id'];

    public function initialize($userId, $description, $hashtags, $dateTime, $interest)
    {
        $this->setUserId($userId);
        $this->setDescription($description);
        $this->setHashtags($hashtags);
        $this->setDateTime($dateTime);
        $this->setInterest($interest);
    }

    public function getTipId()
    {
        return $this->tip_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getHashtags()
    {
        return $this->hashtags;
    }

    public function getDateTime()
    {
        return $this->date_time;
    }

    public function getInterest()
    {
        return $this->interest;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setHashtags($hastags)
    {
        $this->hashtags = $hastags;
    }

    public function setDateTime($dateTime)
    {
        $this->date_time = $dateTime;
    }

    public function setInterest($interest)
    {
        $this->interest = $interest;
    }
}