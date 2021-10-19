<?php

namespace Controllers;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class PostController
{
    public static function createPost($req, $res)
    {

        $body = $req->validateRequest($res);

        $userId = $body->userId;
        $status = $body->status;
        $embeddedMedia = $body->embeddedMedia;
        $mediaType = $body->mediaType;
        $mediaCount = $body->mediaCount;
        $description = $body->description;
        $hashtags = $body->hashtags;
        $interests = $body->interests;
        $dateTime = $body->dateTime;
        $privacyStatus = $body->privacyStatus;



        $post = \Models\Post::create();
        $post->initialize($userId,$status,  $embeddedMedia, $mediaType,  $mediaCount, $description, $hashtags, $interests, $dateTime, $privacyStatus);
        
        $id = \Repository\ORM\ORM::writeObject($post);
        $returnPost = \Repository\ORM\ORM::makePost($id);
        $returnDataArray = \Repository\ORM\DatabaseObject::objectToArray($returnPost);
        $response = new \Helpers\Response();
        $response->setData($returnDataArray);
        $response->setSuccess(true);
        $response->setHttpStatusCode(201);
        $response->addMessage("Post Created");
        $response->send();
        exit;
        
    }
}