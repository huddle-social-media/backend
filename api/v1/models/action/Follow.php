<?php

namespace Models;

class Follow extends HuddleObj
{
    private $user_id;
    private $follower;
    protected $dbTable = 'follow';
    protected $primaryKeysArray = ['user_id', 'follower'];

    public function initialize($userId, $follower)
    {
        $this->setUserId($userId);
        $this->setFollower($follower);
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getFollower()
    {
        return $this->follower;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    public function setFollower($follower)
    {
        $this->follower = $follower;
    }
}