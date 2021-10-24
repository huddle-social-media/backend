<?php

namespace Controllers;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

abstract class PostController
{
    public static function createPost($req, $res)
    {

        if(($body = $req->body()) === null)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Request body is empty or not valid");
            $res->send();
            exit;
        }

        if($req->contentType() !== "application/json")
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Content type invalid");
            $res->send();
            exit;
        }

        
        if(($userId = $body->userId) === null)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("User ID is empty");
            $res->send();
            exit;
        }

        $userStatus = \Repository\ORM\ORM::makeUserStatus($userId);
        if($userStatus->banned === true || $userStatus->status != 'active')
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage(($userStatus->banned === true ? "User is banned": "User is not active"));
            $res->send();
            exit;
        }

        

        $embeddedMedia = $body->embeddedMedia;
        $mediaType = $body->mediaType;
        $mediaCount = $body->mediaCount;
        $description = $body->description;
        $hashtags = $body->hashtags;
        $interests = $body->interests;
        $privacyStatus = $body->privacyStatus;


        $dateTime = date('y/m/d H:i:s', time());
        $post = \Models\Post::create();
        $post->initialize($userId,'active',  $embeddedMedia, $mediaType,  $mediaCount, $description, $hashtags, $interests, $dateTime, $privacyStatus);
        
        $id = \Repository\ORM\ORM::writeObject($post);
        $returnPost = \Repository\ORM\ORM::makePost($id);
        $returnDataArray = \Repository\ORM\DatabaseObject::objectToArray($returnPost);

        $res->setData($returnDataArray);
        $res->setSuccess(true);
        $res->setHttpStatusCode(201);
        $res->addMessage("Post Created");
        $res->send();
        exit;
        
    }


    public static function getPostsByUserId($req, $res)
    {
        echo "Hello";
        $body = $req->body();
        $params = $req->params();
        $userId = $params[0];
        $count = $body->postCount;
        $privacyStatus = $body->privacyStatus;
        
        $postList = \Repository\ORM\ORM::getPostsListbyUserId($userId, $privacyStatus, $count);
        if($postList == null)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(204);
            $res->addMessage("No posts available");
            $res->send();
            exit;
        }else
        {
            $returnDataArray = [];
            foreach($postList as $post)
            {
                $item = \Repository\ORM\DatabaseObject::objectToArray($post);
                $returnDataArray.array_push($item);
            }

            $res->setData($returnDataArray);
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->addMessage("Latest $count posts.");
            $res->send();
            exit;
        }
    }
}