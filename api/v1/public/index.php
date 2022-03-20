<?php
require_once __DIR__."/../helpers/autoLoader/autoLoader.php";

Router\Router::get("/", function(){
    echo "<h1>RooT</h1>";
});

Router\Router::post("/users/casual/signup", Controllers\CasualController::class."::signUp", false);

Router\Router::post("/users/celebrity/signup", Controllers\CelebrityController::class."::signUp", false);

Router\Router::post("/users/organization/signup", Controllers\OrganizationController::class."::signUp", false);

Router\Router::get("/users", Controllers\UserController::class."::checkUsername");

Router\Router::post("/users/sign_in", Controllers\UserController::class."::signIn", false);
Router\Router::post("/users", Controllers\UserController::class."::sayHello");
Router\Router::post("/users/silent_auth", Controllers\AuthController::class."::silentAuthenticate", false);
Router\Router::options("/users/silent_auth", Controllers\UserController::class."::CROS");

Router\Router::options("/users/sign_in", Controllers\UserController::class."::CROS");
Router\Router::options("/users", Controllers\UserController::class."::CROS");

Router\Router::get("/events/attending_events", Controllers\EventController::class."::attendingEvents");
Router\Router::options("/events/attending_events", Controllers\UserController::class."::CROS");

Router\Router::listen(function($port){
    echo "<br><h1>Server up and running on port: $port</h1><br>";
});
