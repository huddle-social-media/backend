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

    public static function adjustTime($obj)
    {
        $time = $obj->getIssueTime();
        $arr = explode(":", $time);
        array_pop($arr);
        $time = join(":", $arr);
        $obj->setIssueTime($time);
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