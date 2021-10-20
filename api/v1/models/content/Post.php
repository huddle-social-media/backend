<?php
namespace Models;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

use PDOException;

class Post extends HuddleObj
{

    // DB attributes
    private $post_id;
    private $user_id;
    private $status;  
    private $embedded_media;
    private $media_type;
    private $media_count;
    private $description;
    private $hashtags = [];
    private $interests = [];
    private $date_time;
    private $privacy_status;


    
    // Objects that link to the Post Object
    private $userObject;
    private $hashtagObjects = []; 
    private $interestObjects = []; 
    private $dateTimeObject;
    private $mentionObjects = [];
    private $likeObjects = [];
    private $commentObjects = [];

    // Attributes needed for ORM
    protected $dbTable = 'post';
    protected $primaryKeysArray = ['post_id'];


    public function initialize($user_id, $status, $embedded_media, $media_type, $media_count, $description, $hashtags, $interests, $date_time, $privacy_status)
    {
        $this->setUserId($user_id);
        $this->setStatus($status);
        $this->setEmbeddedMedia($embedded_media);
        $this->setMediaType($media_type);
        $this->setMediaCount($media_count);
        $this->setDescription($description);
        $this->setHashtags($hashtags);
        $this->setInterests($interests);
        $this->setDateTime($date_time);
        $this->setPrivacyStatus($privacy_status);
        // $this->setMentions($mentions);
        // $this->setLikes($likes);
        // $this->setComments($comments);
    }

    public function createPost($post)
    {
        
    }

    // Getters
    
    public function getPostId()
    {
        return $this->post_id;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getEmbeddedMedia()
    {
        return $this->embedded_media;
    }

    public function getMediaType()
    {
        return $this->media_type;
    }

    public function getMediaCount()
    {
        return $this->media_count;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getHashtags()
    {
        return $this->hashtags;
    }

    public function getInterests()
    {
        return $this->interests;
    }

    public function getDateTime()
    {
        return $this->date_time;
    }

    public function getPrivacyStatus()
    {
        return $this->privacy_status;
    }

    public function getMentions()
    {
        return $this->mentions;
    }

    public function getLikes()
    {
        return $this->likes;
    }

    public function getComments()
    {
        return $this->comments;
    }




    // Setters
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setEmbeddedMedia($embedded_media)
    {
        $this->embedded_media = $embedded_media;
    }

    public function setMediaType($media_type)
    {
        $this->media_type = $media_type;
    }

    public function setMediaCount($media_count)
    {
        $this->media_count = $media_count;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setHashtags($hashtags)
    {
        $this->hashtags = $hashtags;
    }

    public function setInterests($interests)
    {
        $this->interests = $interests;
    }

    public function setDateTime($date_time)
    {
        $this->date_time = $date_time;
    }

    public function setPrivacyStatus($privacy_status)
    {
        $this->privacy_status = $privacy_status;
    }

    public function setMentions($mentions)
    {
        $this->mentions = $mentions;
    }

    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }





}