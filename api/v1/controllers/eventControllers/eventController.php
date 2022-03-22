<?php

namespace Controllers;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";



abstract class EventController{

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

    public static function addCollaborators($event, $type)
    {
        $collabList = \Repository\ORM\ORM::getCollaboratorList($event['event_id'], $type);

        if($collabList == null)
        {
            return $event;
        }

        if(is_array($collabList))
        {
            foreach($collabList as $collab)
            {

                $temp = \Repository\ORM\DatabaseObject::objectToArray($collab);
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                unset($temp['password']);
                unset($temp['status']);
                unset($temp['state']);
                unset($temp['login_attempts']);
                unset($temp['banned']);
                unset($temp['gender']);
                unset($temp['dob']);
                $event['attendingCelebs'][] = $temp;
            }

        }else
        {
            $temp = \Repository\ORM\DatabaseObject::objectToArray($collabList);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            unset($temp['password']);
            unset($temp['status']);
            unset($temp['state']);
            unset($temp['login_attempts']);
            unset($temp['banned']);
            unset($temp['gender']);
            unset($temp['dob']);
            $event['attendingCelebs'][] = $temp;
        
        }

        return $event;

    }

    public static function adjustTime($obj)
    {
        $time = $obj->getEventTime();
        $arr = explode(":", $time);
        array_pop($arr);
        $time = join(":", $arr);
        $obj->setEventTime($time);
        return $obj;
    }

    public static function checkAttendanceOfEvent($array, $obj)
    {
        if(in_array($obj['event_id'], $array))
        {
            $obj['state'] = "attending";
        }else
        {
            $obj['state'] = "notAttending";
        }

        return $obj;
    }

    public static function allEvents($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        $eventList = \Repository\ORM\ORM::getEventList();

        $dataArray = [];

        $attendingEvents = \Repository\ORM\ORM::getAttendingEventIdList($userId);

        $eventIdList = [];

        if(is_array($attendingEvents) && count($attendingEvents) > 1)
        {
            
            foreach($attendingEvents as $event)
            {
                if(is_array($event))
                {
                    $eventIdList[] = $event['event_id'];
                }else
                {
                    $eventIdList[] = $event;
                }
                
            }
        }

        if($eventList == null)
        {
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->setData(null);
            $res->send();
            exit;
        }

        if(is_array($eventList))
        {
            foreach($eventList as $event)
            {
                $event = self::adjustTime($event);
                $temp = \Repository\ORM\DatabaseObject::objectToArray($event);
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                $temp = self::checkAttendanceOfEvent($eventIdList, $temp);
                $temp = self::addCollaborators($temp, 'celebrity');
                $temp = self::addCollaborators($temp, 'organization');
                $dataArray[] = $temp;
            }

        }else
        {
            $eventList = self::adjustTime($eventList);
            $temp = \Repository\ORM\DatabaseObject::objectToArray($eventList);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $temp = self::checkAttendanceOfEvent($eventIdList, $temp);
            $temp = self::addCollaborators($temp, 'celebrity');
                $temp = self::addCollaborators($temp, 'organization');
            $dataArray[] = $temp;
            
        }

        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->setData($dataArray);
        $res->send();
        exit;
         
    }

    public static function attendingEvents($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        $eventList = \Repository\ORM\ORM::getAttendingEvents(intval($userId));

        $dataArray = [];

        if($eventList == null)
        {
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->setData(null);
            $res->send();
            exit;
        }

        if(is_array($eventList))
        {
            foreach($eventList as $event)
            {
                $event = self::adjustTime($event);
                $temp = \Repository\ORM\DatabaseObject::objectToArray($event);
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                $temp['state'] = "attending";
                $temp = self::addCollaborators($temp, 'celebrity');
                $temp = self::addCollaborators($temp, 'organization');
                $dataArray[] = $temp;
            }

        }else
        {
            $eventList = self::adjustTime($eventList);
            $temp = \Repository\ORM\DatabaseObject::objectToArray($eventList);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $temp['state'] = "attending";
            $temp = self::addCollaborators($temp, 'celebrity');
            $temp = self::addCollaborators($temp, 'organization');
            $dataArray[] = $temp;
            
        }
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->setData($dataArray);
        $res->send();
        exit;
    }

    public static function leaveEvent($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        if(empty($req->body()) || !isset($req->body()->event_id))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("The Body is not complete");
            $res->send();
            exit;
        }

        $eventAttendant = \Repository\ORM\ORM::makeEventAttendant($req->body()->event_id, $userId);
        $eventAttendant->setStatus('deactive');
        \Repository\ORM\ORM::updateObject($eventAttendant);
        $dataArray = \Repository\ORM\DatabaseObject::objectToArray($eventAttendant);
        unset($dataArray['dbTable']);
        unset($dataArray['primaryKeysArray']);
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->addMessage("Item modified successfully");
        $res->setData($dataArray);
        $res->send();
        exit;



    }

    public static function attendEvent($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        if(empty($req->body()) || !isset($req->body()->event_id))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("The Body is not complete");
            $res->send();
            exit;
        }

        if(\Repository\ORM\ORM::checkEventAttendant($req->body()->event_id, $userId))
        {
            $eventAttendant = \Models\EventAttendant::create();
            $eventAttendant->initialize($req->body()->event_id, $userId);
            $eventAttendant->setStatus('active');
            \Repository\ORM\ORM::updateObject($eventAttendant);
            $dataArray = \Repository\ORM\DatabaseObject::objectToArray($eventAttendant);
        }else
        {
            $eventAtt = \Models\EventAttendant::create();
            $eventAtt->initialize($req->body()->event_id, $userId);
            $id = \Repository\ORM\ORM::writeObject($eventAtt);
            $dataArray = \Repository\ORM\DatabaseObject::objectToArray($eventAtt);
            $dataArray['id'] = $id;

        } 
        unset($dataArray['dbTable']);
        unset($dataArray['primaryKeysArray']);
        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->addMessage("Item modified successfully");
        $res->setData($dataArray);
        $res->send();
        exit;



    }

    public static function allPostedEvents($req, $res, $userId)
    {
        self::initialCheck($req, $res);

        $eventList = \Repository\ORM\ORM::getPostedEvents(intval($userId));

        $dataArray = [];

        if($eventList == null)
        {
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->setData(null);
            $res->send();
            exit;
        }

        if(is_array($eventList))
        {
            foreach($eventList as $event)
            {
                $event = self::adjustTime($event);
                $temp = \Repository\ORM\DatabaseObject::objectToArray($event);
                unset($temp['dbTable']);
                unset($temp['primaryKeysArray']);
                $temp = self::addCollaborators($temp, 'celebrity');
                $temp = self::addCollaborators($temp, 'organization');
                $dataArray[] = $temp;
            }

        }else
        {
            $eventList = self::adjustTime($eventList);
            $temp = \Repository\ORM\DatabaseObject::objectToArray($eventList);
            unset($temp['dbTable']);
            unset($temp['primaryKeysArray']);
            $temp = self::addCollaborators($temp, 'celebrity');
            $temp = self::addCollaborators($temp, 'organization');
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