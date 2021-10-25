<?php

namespace Controllers;
require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

abstract class TipController
{
    public static function createTip($req, $res)
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

        $description = $body->description;
        $hashtags = $body->hashtags;
        $interests = $body->interests;
        $dateTime = date('y/m/d H:i:s', time());

        $tip = \Models\Tip::create();
        $tip->initialize($userId, $description, $hashtags, $dateTime, $interests);
        $id = \Repository\ORM\ORM::writeObject($tip);
        $returnTip = \Repository\ORM\ORM::makeTip($id);
        $returnDataArray = \Repository\ORM\DatabaseObject::objectToArray($returnTip);

        $res->setData($returnDataArray);
        $res->setSuccess(true);
        $res->setHttpStatusCode(201);
        $res->addMessage("Tip Created");
        $res->send();
        exit;
    }

    public static function getTipsByUser($req, $res)
    {
        $body = $req->body();
        $userId = $body->userId;
        $count = $body->limit;

        $tipsList = \Repository\ORM\ORM::getTipsListbyUserId($userId, $count);
        if($tipsList == null)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(204);
            $res->addMessage("No tips available");
            $res->send();
            exit;
        }else
        {
            $returnDataArray = [];
            if(is_array($tipsList))
            {
                for($i = 0; $i < count($tipsList); $i++)
                {
                    $item = \Repository\ORM\DatabaseObject::objectToArray($tipsList[$i]);
                    $returnDataArray += [$i => $item];
                }
            }else
            {
                $returnDataArray = \Repository\ORM\DatabaseObject::objectToArray($tipsList);
            }
            

            $res->setData($returnDataArray);
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->addMessage("Latest $count tips.");
            $res->send();
            exit;
        }
    }
}