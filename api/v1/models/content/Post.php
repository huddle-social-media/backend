<?php
namespace Models;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

use PDOException;

class Post
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

    // The relation that stores object info in DB
    private $dbTable = 'post';

   /**
    * Generates a Post Object to work with.
    */
    public static function create()
    {
        return new self();
    }

    public function initialize($userId, $status, $embeddedMedia, $mediaType, $mediaCount, $description, $hashtags, $interests, $dateTime, $privacyStatus)
    {
        $this->setUserId($userId);
        $this->setStatus($status);
        $this->setEmbeddedMedia($embeddedMedia);
        $this->setMediaType($mediaType);
        $this->setMediaCount($mediaCount);
        $this->setDescription($description);
        $this->setHashtags($hashtags);
        $this->setInterests($interests);
        $this->setDateTime($dateTime);
        $this->setPrivacyStatus($privacyStatus);
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

    public function getDbTable()
    {
        return $this->dbTable;
    }



    // Setters
    public function setPostId($postId)
    {
        $this->post_id = $postId;
    }

    public function setUserId($userId)
    {
        $this->user_id = $userId;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setEmbeddedMedia($embeddedMedia)
    {
        $this->embedded_media = $embeddedMedia;
    }

    public function setMediaType($mediaType)
    {
        $this->media_type = $mediaType;
    }

    public function setMediaCount($mediaCount)
    {
        $this->media_count = $mediaCount;
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

    public function setDateTime($dateTime)
    {
        $this->date_time = $dateTime;
    }

    public function setPrivacyStatus($privacyStatus)
    {
        $this->privacy_status = $privacyStatus;
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