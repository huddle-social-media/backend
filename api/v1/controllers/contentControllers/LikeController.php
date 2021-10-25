<?php
namespace Controllers;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class LikeController
{

    public static function addLike($req, $res){
    
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
        

        $postId=$body->postId;

        $like = \Models\Like::create();
        $dateTime = date('y/m/d H:i:s', time());
        $like->initialize($postId,$userId, $dateTime);
        \Repository\ORM\ORM::writeObject($like);
        $post = \Repository\ORM\ORM::makePost($postId);
        $total_like_count = $post->getLikeCount() + 1;
        $post->setLikeCount($total_like_count);
        \Repository\ORM\ORM::updateObject($post);
        $returnlike = \Repository\ORM\ORM::makeLike($postId, $userId);
        $returnDataArray = \Repository\ORM\DatabaseObject::objectToArray($returnlike);
        $returnDataArray += ['total_like_count' => $total_like_count];
        $res = new \Helpers\Response();
        $res->setData($returnDataArray);
        $res->setSuccess(true);
        $res->setHttpStatusCode(201);
        $res->addMessage("Like added");
        $res->send();
        exit;

    }
/*
    public static function likecount(){
    $postid=$body->postid;
    $likecount=\models\Like::create();
    $likecount->initial($postid);
    $returncount=\Repository\ORM\ORM::likecount($likecount);//$returnlike=\Repository\ORM\ORM::getlikecount($id);
    $returnDataArray =\Repository\ORM\DatabaseObject::objectToArray($returncount);
    $response->setData($returnDataArray);
    $response->setSuccess(true);
    $response->setHttpStatusCode(201);
    $response->send();
    exit;
   }*/



}