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
                $temp['me'] = $userId;
                $dataArray[] = $temp;
            }

        }else
        {
            $issueList = self::adjustTime($issueList);
            $temp = \Repository\ORM\DatabaseObject::objectToArray($issueList);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $temp['me'] = $userId;
            $dataArray[] = $temp;
            
        }
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->setData($dataArray);
        $res->send();
        exit;

    }

    public static function rejectIssue($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        $body = $req->body();

        if(!isset($body) && !isset($body->issue_id))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(500);
            $res->addMessage("System error.");
            $res->send();
            exit;
        }

        $issue_id = $body->issue_id;

        $acc_user = \Repository\ORM\ORM::getIssueAcceptedUser($issue_id);

        $issue = \Repository\ORM\ORM::makeIssue($issue_id);
        $issue->setState('pending');
        \Repository\ORM\ORM::updateObject($issue);

        if($acc_user == null)
        {
            $obj = \Models\IssueAccepted::create();
            $obj->initialize($issue_id, $userId, 'closed', date("Y-m-d"), date("H:i:s"), 'rejected');
            \Repository\ORM\ORM::writeObject($obj);
            $dataArray = \Repository\ORM\DatabaseObject::objectToArray($obj);
            $res->setSuccess(true);
            $res->setHttpStatusCode(201);
            $res->addMessage('Issue Rejected');
            $res->setData($dataArray);
            $res->send();
            exit;
        }else
        {
            $obj = \Repository\ORM\ORM::makeIssueAccepted($issue_id, $userId);
            $obj->setState('rejected');
            \Repository\ORM\ORM::updateObject($obj);
            $dataArray = \Repository\ORM\DatabaseObject::objectToArray($obj);
            $res->setSuccess(true);
            $res->setHttpStatusCode(201);
            $res->addMessage('Issue Rejected');
            $res->setData($dataArray);
            $res->send();
            exit;

        }


    }

    public static function myPendingIssues($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        $issueList = \Repository\ORM\ORM::getMyPendingIssues($userId);

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
                $temp['me'] = $userId;
                $dataArray[] = $temp;
            }

        }else
        {
            $issueList = self::adjustTime($issueList);
            $temp = \Repository\ORM\DatabaseObject::objectToArray($issueList);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $temp['me'] = $userId;
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

        if(is_array($closedList) && count($closedList) > 1 && $closedList != null)
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
                $issue_id = $temp['issue_id'];
                $accUser = \Repository\ORM\ORM::getIssueAcceptedUser($issue_id);
                if($accUser == null)
                {
                    $res->setSuccess(false);
                    $res->setHttpStatusCode(500);
                    $res->addMessage("System error.");
                    $res->send();
                    exit;
                }
                $accUser = $accUser['accepted_user'];
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                
                $temp = self::getChatStatus($temp, $accUser);
                $temp = self::getMessage($temp, $res, $userId);
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
            $issue_id = $temp['issue_id'];
            $accUser = \Repository\ORM\ORM::getIssueAcceptedUser($issue_id);
            if($accUser == null)
            {
                $res->setSuccess(false);
                $res->setHttpStatusCode(400);
                $res->addMessage("System error.");
                $res->send();
                exit;
            }
            $accUser = $accUser['accepted_user'];
            $temp = self::getChatStatus($temp, $accUser);
            $temp = self::getMessage($temp, $res, $userId);
            
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
        $from = $postedUser;

        if($user_id == $accUser)
        {
            $sentTo = $postedUser;
            $from = $accUser;
        }


        $myMessages = \Repository\ORM\ORM::getReadIssueChatById($issue_id, $from, $sentTo );


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

        $unreadMessages = \Repository\ORM\ORM::getUnreadIssueChatById($issue_id, $from, $sentTo);

        $myUnreadArray = [];

        if(is_array($unreadMessages))
        {
            foreach($unreadMessages as $message)
            {
                $message = self::adjustTime($message, "getMessageTime", "setMessageTime");
                $temp = \Repository\ORM\DatabaseObject::objectToArray($message);
                if($temp['sent_status'] == 'not sent')
                {
                    $message->setSentStatus('sent');
                    \Repository\ORM\ORM::updateObject($message);
                }
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                $myUnreadArray[] = $temp;
            }

        }elseif($unreadMessages != null)
        {
            $unreadMessages = self::adjustTime($unreadMessages,  "getMessageTime", "setMessageTime");
            $temp = \Repository\ORM\DatabaseObject::objectToArray($unreadMessages);
            if($temp['sent_status'] == 'not sent')
                {
                    $unreadMessages->setSentStatus('sent');
                    \Repository\ORM\ORM::updateObject($unreadMessages);
                }
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $myUnreadArray[] = $temp;
        }

        $obj['messages'] = $myMessArray;
        $obj['unReadMessages'] = $myUnreadArray;

        return $obj;


    }

    public static function getUnSentIssueChat($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        $messageList = \Repository\ORM\ORM::getUnSentIssueChat($userId);

        $myUnSentArray = [];

        if(is_array($messageList))
        {
            foreach($messageList as $message)
            {
                $message = self::adjustTime($message, "getMessageTime", "setMessageTime");
                $temp = \Repository\ORM\DatabaseObject::objectToArray($message);
                if($temp['sent_status'] == 'not sent')
                {
                    $message->setSentStatus('sent');
                    \Repository\ORM\ORM::updateObject($message);
                }
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                unset($myUnSentArray['extraAttr']);
                $myUnSentArray[] = $temp;
            }

        }elseif($messageList != null)
        {
            $messageList = self::adjustTime($messageList,  "getMessageTime", "setMessageTime");
            $temp = \Repository\ORM\DatabaseObject::objectToArray($messageList);
            if($temp['sent_status'] == 'not sent')
                {
                    $messageList->setSentStatus('sent');
                    \Repository\ORM\ORM::updateObject($messageList);
                }
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            unset($myUnSentArray['extraAttr']);
            $myUnSentArray[] = $temp;
        }
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->setData($myUnSentArray);
        $res->send();
        exit;


    }

    public static function acceptIssue($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        if($req->body() == null || !isset($req->body()->issue_id))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Bad Request.");
            $res->send();
            exit;
        }

        $body = $req->body();

        $issueId = $body->issue_id;

        if((\Repository\ORM\ORM::checkIssueAcceptedUser($issueId)) || (\Repository\ORM\ORM::checkAcceptedUserInTable($issueId, $userId)))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Conflict occured due to invalid request.");
            $res->send();
            exit;
        }

        $issueAccepted = \Models\IssueAccepted::create();
        $issueAccepted->initialize($issueId, $userId, 'open', date("Y-m-d"), date("H:i:s"), 'open');
        \Repository\ORM\ORM::writeObject($issueAccepted);
        $issue = \Repository\ORM\ORM::makeIssue($issueId);
        $issue->setState('accepted');
        \Repository\ORM\ORM::updateObject($issue);
        $dataArray = \Repository\ORM\DatabaseObject::objectToArray($issueAccepted);
        unset($dataArray['dbTable']);
        unset($dataArray['primaryKeysArray']);
        unset($dataArray['extraAttr']);
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->addMessage("Object successfully created");
        $res->setData($dataArray);
        $res->send();
        exit;

    }

    public static function markIssueChatAsRead($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        if($req->body() == null || !isset($req->body()->unReadMessages))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Bad Request.");
            $res->send();
            exit;
        }

        $body = $req->body();

        $issueId = $body->issue_id;

        $unReadMessages = $body->unReadMessages;

        $dataArray = [];

        foreach($unReadMessages as $id)
        {
            $temp = \Repository\ORM\ORM::makeIssueChat($id);
            $temp->setReadStatus('read');
            \Repository\ORM\ORM::updateObject($temp);
            $temp = \Repository\ORM\DatabaseObject::objectToArray($temp);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            unset($temp['extraAttr']);
            $dataArray[] = $temp;

            
        }

        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->addMessage("Objects changed successfully.");
        $res->setData($dataArray);
        $res->send();
        exit;


    }

    public static function sendIssueChat($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        if($req->body() == null || !isset($req->body()->message) || !isset($req->body()->sent_to) || !isset($req->body()->issue_id))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Bad Request.1");
            $res->send();
            exit;
        }

        $body = $req->body();

        $issueId = $body->issue_id;
        $sentTo = $body->sent_to;
        $repliedTo = $body->replied_to;
        $message = $body->message;

        $accUser = \Repository\ORM\ORM::getIssueAcceptedUser($issueId);

        if($accUser == null)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Bad Request.");
            $res->send();
            exit;
        }
        $accUser = $accUser['accepted_user'];

        $issueobj = \Repository\ORM\ORM::makeIssue($issueId);
        $postedUser = $issueobj->getUserId();

        if(($userId != $postedUser && $userId != $accUser) || ($sentTo != $postedUser && $sentTo != $accUser) || $message == "")
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Bad Request. 2");
            $res->send();
            exit;
        }

        $issueChatObj = \Models\IssueChat::create();
        $issueChatObj->initialize(null, $userId, $issueId, $message, null, date("Y-m-d"), date("H:i:s"), null, null, $sentTo, 'not sent');
        $id = \Repository\ORM\ORM::writeObject($issueChatObj);
        $issueChatObj->setMessageId($id);
        $dataArray = \Repository\ORM\DatabaseObject::objectToArray($issueChatObj);
        $dataArray['me'] = $userId;
        unset($dataArray['dbTable']);
        unset($dataArray['primaryKeysArray']);
        unset($dataArray['extraAttr']);
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->addMessage("Chat created successfully.");
        $res->setData($dataArray);
        $res->send();
        exit;
        

    }

    public static function createIssue($req, $res, $userId)
    {
        //check for correct user type

        self::initialCheck($req, $res);

        $body = $req->body();

        //Add backend validation

        $obj = \Models\Issue::create();
        $obj->initialize($userId, 'pending', $body->title, $body->description, $body->interest, $body->embedded_media, $body->media_count, 'active');
        $id = \Repository\ORM\ORM::writeObject($obj);
        $obj->setIssueId($id);
        $dataArray = \Repository\ORM\DatabaseObject::objectToArray($obj);
        unset($dataArray['dbTable']);
        unset($dataArray['primaryKeysArray']);
        unset($dataArray['extraAttr']);
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->addMessage("Issue created successfully.");
        $res->setData($dataArray);
        $res->send();
        exit;

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