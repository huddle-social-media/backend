<?php
namespace Controllers;

use Exception;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

abstract class FollowController
{
    public static function addFollower($req, $res)
    {
        $body = $req->body();
        $userId = $body->userId;
        $follower = $body->follower;

        $follow = \Models\Follow::create();
        $follow->initialize($userId, $follower);
        \Repository\ORM\ORM::writeObject($follow);
        try
        {
            $returnFollow = \Repository\ORM\ORM::makeFollow($userId, $follower);
            $returnData = \Repository\ORM\DatabaseObject::objectToArray($returnFollow);

            $res->setSuccess(true);
            $res->setData($returnData);
            $res->setHttpStatusCode(201);
            $res->addMessage("Follow recorded successfully");
            $res->send();
            exit;
        }catch(Exception $ex)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }
        
        

    }
}