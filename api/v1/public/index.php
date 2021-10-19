<?php

require_once __DIR__."/../helpers/autoLoader/autoLoader.php";


\Router\Router::get('/', function($req, $res){
    $res->setHttpStatusCode(200);
    $res->setSuccess(true);
    $res->addMessage("Hey this is the controller");
    $res->send();
    
});

\Router\Router::post('/posts', '\Controllers\PostController::createPost');

\Router\Router::listen();