<?php

namespace Controllers;

class ChatController
{

    public static function adjustTime($obj, $getmethod = "getIssueTime", $setmethod = "setIssueTime")
    {
        $time = $obj->$getmethod();
        $arr = explode(":", $time);
        array_pop($arr);
        $time = join(":", $arr);
        $obj->$setmethod($time);
        return $obj;
    }

    // public static function getAllChats($req, $res, $userId)
    // {
        

    //     // if(!isset($req->body()) || !isset($req->body()->issue_id))
    //     // {
    //     //     $res->setSuccess(false);
    //     //     $res->setHttpStatusCode(400);
    //     //     $res->addMessage("Bad Request.");
    //     //     $res->send();
    //     //     exit;
    //     // }

    //     // $body = $req->body();


    //     $myMessages = \Repository\ORM\ORM::getAllReadChats($userId);



    //     $myMessArray = [];

    //     if(is_array($myMessages))
    //     {
    //         foreach($myMessages as $message)
    //         {
    //             $message = self::adjustTime($message, "getMessageTime", "setMessageTime");
    //             $temp = \Repository\ORM\DatabaseObject::objectToArray($message);
    //             unset($temp['dbTable']);
    //             unset($temp['primaryKeysArray']);
    //             $myMessArray[] = $temp;
    //         }

    //     }elseif($myMessages != null)
    //     {
    //         $myMessages = self::adjustTime($myMessages,  "getMessageTime", "setMessageTime");
    //         $temp = \Repository\ORM\DatabaseObject::objectToArray($myMessages);
    //         unset($temp['dbTable']);
    //         unset($temp['primaryKeysArray']);
    //         $myMessArray[] = $temp;
    //     }

    //     $unreadMessages = \Repository\ORM\ORM::getUnreadIssueChatById($issue_id, $user_id, $sentTo);

    //     $myUnreadArray = [];

    //     if(is_array($unreadMessages))
    //     {
    //         foreach($unreadMessages as $message)
    //         {
    //             $message = self::adjustTime($message, "getMessageTime", "setMessageTime");
    //             $temp = \Repository\ORM\DatabaseObject::objectToArray($message);
    //             if($temp['sent_status'] == 'not sent')
    //             {
    //                 $message->setSentStatus('sent');
    //                 \Repository\ORM\ORM::updateObject($message);
    //             }
    //             unset($temp['dbTable']);
    //             unset($temp['primaryKeysArray']);
    //             $myUnreadArray[] = $temp;
    //         }

    //     }elseif($unreadMessages != null)
    //     {
    //         $unreadMessages = self::adjustTime($unreadMessages,  "getMessageTime", "setMessageTime");
    //         $temp = \Repository\ORM\DatabaseObject::objectToArray($unreadMessages);
    //         if($temp['sent_status'] == 'not sent')
    //             {
    //                 $unreadMessages->setSentStatus('sent');
    //                 \Repository\ORM\ORM::updateObject($unreadMessages);
    //             }
    //         unset($temp['dbTable']);
    //         unset($temp['primaryKeysArray']);
    //         $myUnreadArray[] = $temp;
    //     }

    //     $obj['messages'] = $myMessArray;
    //     $obj['unReadMessages'] = $myUnreadArray;

    //     return $obj;


    // }
}