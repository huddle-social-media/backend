<?php

namespace Controllers;

class IssueController
{

    public static function initialCheck($req, $res)
    {

        if($req->contentType() !== "application/json")
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Content type invalid");
            $res->send();
            exit;
        }

        
    }

    public static function adjustTime($obj, $getmethod = "getIssueTime", $setmethod = "setIssueTime")
    {
        $time = $obj->$getmethod();
        $arr = explode(":", $time);
        array_pop($arr);
        $time = join(":", $arr);
        $obj->$setmethod($time);
        return $obj;
    }

    public static function issuesOnUser($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        $issueList = \Repository\ORM\ORM::getIssuesOnUser($userId);

        $dataArray = [];

        if($issueList == null)
        {
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->setData(null);
            $res->send();
            exit;
        }

        if(is_array($issueList))
        {
            foreach($issueList as $issue)
            {
                $issue = self::adjustTime($issue);
                $temp = \Repository\ORM\DatabaseObject::objectToArray($issue);
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                $dataArray[] = $temp;
            }

        }else
        {
            $issueList = self::adjustTime($issueList);
            $temp = \Repository\ORM\DatabaseObject::objectToArray($issueList);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $dataArray[] = $temp;
            
        }
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->setData($dataArray);
        $res->send();
        exit;

    }

    public static function checkClosedStateOfIssue($array, $obj)
    {
        if(in_array($obj['issue_id'], $array))
        {
            $obj['state'] = "closed";
        }else
        {
            $obj['state'] = "open";
        }

        return $obj;
    }

    public static function getChatStatus($obj, $userId)
    {
        $issueId = $obj['issue_id'];
        $status = \Repository\ORM\ORM::getChatStatus($issueId, $userId);
        if($status != null && $status['chat_status'] === 'open')
        {
            $obj['chat_status'] = 'open';
        }else {
            $obj['chat_status'] = 'closed';
        }

        return $obj;
    }

    

    public static function issuesAcceptedByUser($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        $issueList = \Repository\ORM\ORM::getIssuesAcceptedByUser($userId);


        $closedList = \Repository\ORM\ORM::getClosedIssuesByUser($userId);



        $issueIdList = [];

        if(is_array($closedList) && count($closedList) > 1)
        {
            
            foreach($closedList as $item)
            {
                if(is_array($item))
                {
                    $issueIdList[] = $item['issue_id'];
                }else
                {
                    $issueIdList[] = $item;
                }
                
            }
        }

        if($issueList == null)
        {
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->setData(null);
            $res->send();
            exit;
        }

        if(is_array($issueList))
        {
            foreach($issueList as $issue)
            {
                $issue = self::adjustTime($issue);
                $temp = \Repository\ORM\DatabaseObject::objectToArray($issue);
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                $temp = self::checkClosedStateOfIssue($issueIdList, $temp);
                $temp = self::getChatStatus($temp, $userId);
                $temp = self::getMessage($temp, $res, $userId);
                $temp['me'] = $userId;
                $dataArray[] = $temp;
                
            }

        }else
        {
            
            $issueList = self::adjustTime($issueList);
            $temp = \Repository\ORM\DatabaseObject::objectToArray($issueList);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $temp = self::checkClosedStateOfIssue($issueIdList, $temp);
            $temp = self::getChatStatus($temp, $userId);
            $temp = self::getMessage($temp, $res, $userId);
            $temp['me'] = $userId;
            $dataArray[] = $temp;
            
        }


        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->setData($dataArray);
        $res->send();
        exit;
        
    }

    public static function myAcceptedIssues($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        $issueList = \Repository\ORM\ORM::getMyAcceptedIssues($userId);



        $issueIdList = [];

        if($issueList == null)
        {
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->setData(null);
            $res->send();
            exit;
        }

        if(is_array($issueList))
        {
            foreach($issueList as $issue)
            {
                $issue = self::adjustTime($issue);
                $temp = \Repository\ORM\DatabaseObject::objectToArray($issue);
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                $temp = self::checkClosedStateOfIssue($issueIdList, $temp);
                $temp = self::getChatStatus($temp, $userId);
                $temp = self::getMessage($temp, $res, $userId);
                $accUser = \Repository\ORM\ORM::getIssueAcceptedUser($temp->issue_id);
                if($accUser == null)
                {
                    $res->setSuccess(false);
                    $res->setHttpStatusCode(400);
                    $res->addMessage("System error.");
                    $res->send();
                    exit;
                }
                $accUser = $accUser['accepted_user'];
                $temp['accepted_user'] = $accUser;
                $temp['me'] = $userId;
                $dataArray[] = $temp;
                
            }

        }else
        {
            
            $issueList = self::adjustTime($issueList);
            $temp = \Repository\ORM\DatabaseObject::objectToArray($issueList);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $temp = self::checkClosedStateOfIssue($issueIdList, $temp);
            $temp = self::getChatStatus($temp, $userId);
            $temp = self::getMessage($temp, $res, $userId);
            $accUser = \Repository\ORM\ORM::getIssueAcceptedUser($temp->issue_id);
            if($accUser == null)
            {
                $res->setSuccess(false);
                $res->setHttpStatusCode(400);
                $res->addMessage("System error.");
                $res->send();
                exit;
            }
            $accUser = $accUser['accepted_user'];
            $temp['accepted_user'] = $accUser;
            $temp['me'] = $userId;
            $dataArray[] = $temp;
            
        }


        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->setData($dataArray);
        $res->send();
        exit;
    }

    public static function getMessage($obj, $res, $user_id)
    {
        

        // if(!isset($req->body()) || !isset($req->body()->issue_id))
        // {
        //     $res->setSuccess(false);
        //     $res->setHttpStatusCode(400);
        //     $res->addMessage("Bad Request.");
        //     $res->send();
        //     exit;
        // }

        // $body = $req->body();
        $issue_id = $obj['issue_id'];
        $accUser = \Repository\ORM\ORM::getIssueAcceptedUser($issue_id);
        $postedUser = $obj['user_id'];

        if($accUser == null)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Bad Request.");
            $res->send();
            exit;
        }
        $accUser = $accUser['accepted_user'];

        $sentTo = $accUser;

        if($user_id == $accUser)
        {
            $sentTo = $postedUser;
        }


        $myMessages = \Repository\ORM\ORM::getReadIssueChatById($issue_id, $user_id, $postedUser );


        $myMessArray = [];

        if(is_array($myMessages))
        {
            foreach($myMessages as $message)
            {
                $message = self::adjustTime($message, "getMessageTime", "setMessageTime");
                $temp = \Repository\ORM\DatabaseObject::objectToArray($message);
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                $myMessArray[] = $temp;
            }

        }elseif($myMessages != null)
        {
            $myMessages = self::adjustTime($myMessages,  "getMessageTime", "setMessageTime");
            $temp = \Repository\ORM\DatabaseObject::objectToArray($myMessages);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $myMessArray[] = $temp;
        }

        $unreadMessages = \Repository\ORM\ORM::getUnreadIssueChatById($issue_id, $user_id, $sentTo);

        $myUnreadArray = [];

        if(is_array($unreadMessages))
        {
            foreach($unreadMessages as $message)
            {
                $message = self::adjustTime($message, "getMessageTime", "setMessageTime");
                $temp = \Repository\ORM\DatabaseObject::objectToArray($message);
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                $myUnreadArray[] = $temp;
            }

        }elseif($unreadMessages != null)
        {
            $myMessages = self::adjustTime($unreadMessages,  "getMessageTime", "setMessageTime");
            $temp = \Repository\ORM\DatabaseObject::objectToArray($unreadMessages);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $myUnreadArray[] = $temp;
        }

        $obj['messages'] = $myMessArray;
        $obj['unReadMessages'] = $myUnreadArray;

        return $obj;





    }

    public static function CROS($req, $res) {
        header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PATCH');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Max-Age: 5184000');
        header('Access-Control-Allow-Origin: https://huddle.com');
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->send();
        exit;
    }
}