<?php
namespace Models;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

use PDOException;

class Like extends HuddleObj
{
    private $post_id;
    private $user_id;
    private $date_time;

    protected $dbTable = 'like';
    protected $primaryKeysArray = ['post_id','user_id'];

    public function initialize($postid,$userid, $dateTime){
        $this->setPostId($postid);
        $this->setUserId($userid);
        $this->setDateTime($dateTime);
    }


    public function getPostId()
    {
        return $this->postid;
    }

    public function getUserId()
    {
        return $this->userid;
    }
    
    public function getDateTime()
    {
        return $this->date_time;
    }

    public function setPostId($postid)
    {
        $this->post_id = $postid;
    }

    public function setUserId($userid)
    {
        $this->user_id = $userid;
    }

    public function setDateTime($dateTime)
    {
        $this->date_time = $dateTime;
    }    
}