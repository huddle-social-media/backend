<?php

require_once __DIR__."/../router/Router.php";
require_once __DIR__."/../controllers/userControllers/UserController.php";
require_once __DIR__."/../controllers/userControllers/CasualController.php";
require_once __DIR__."/../controllers/userControllers/CelebrityController.php";
require_once __DIR__."/../controllers/userControllers/OrganizationController.php";
require_once __DIR__."/../auth/controllers/AuthController.php";

Router\Router::get("/", function(){
    echo "<h1>RooT</h1>";
});

Router\Router::post("/users/casual/signup", Controllers\CasualController::class."::signUp", false);

Router\Router::post("/users/celebrity/signup", Controllers\CelebrityController::class."::signUp", false);

Router\Router::post("/users/organization/signup", Controllers\OrganizationController::class."::signUp", false);

Router\Router::get("/users", Controllers\UserController::class."::checkUsername");

Router\Router::post("/users/login", Controllers\UserController::class."::login", false);
Router\Router::post("/users", Controllers\UserController::class."::sayHello");
Router\Router::post("/users/silentAuth", Controllers\AuthController::class."::silentAuthenticate", false);

Router\Router::listen(function($port){
    echo "<br><h1>Server up and running on port: $port</h1><br>";
});