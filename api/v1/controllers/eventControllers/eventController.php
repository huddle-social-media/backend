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

    public static function attendingEvents($req, $res)
    {

        header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Max-Age: 5184000');

        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->send();
        exit;
        

        
    }
}