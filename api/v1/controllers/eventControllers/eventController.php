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
        self::initialCheck($req, $res);

        


        $res->setSuccess(true);
        $res->setHttpStatusCode(200);
        $res->setData(["Hello" => "This is fun"]);
        $res->send();
        exit;
        

        
    }
}