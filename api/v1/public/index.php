<?php


require_once __DIR__."/../helpers/autoLoader/autoLoader.php";

Router\Router::get("/", function(){
    echo "<h1>RooT</h1>";
});

// unsecured routes, these routes don't require auth api
Router\Router::post("/users/casual/signup", Controllers\CasualController::class."::signUp", false);
Router\Router::post("/users/celebrity/signup", Controllers\CelebrityController::class."::signUp", false);
Router\Router::post("/users/organization/signup", Controllers\OrganizationController::class."::signUp", false);
Router\Router::post("/users/login", Controllers\UserController::class."::login", false);
Router\Router::options("/users/login", Controllers\UserController::class."::enableCrosOrigin", false);

// partially use auth functionalities
Router\Router::post("/users/silentAuth", Controllers\AuthController::class."::silentAuthenticate", false);

// secured routes for testing purposes
Router\Router::get("/users", Controllers\UserController::class."::checkUsername");
Router\Router::post("/users", Controllers\UserController::class."::sayHello");
Router\Router::get("/posts", Controllers\PostController::class."::getPosts");
Router\Router::post("/posts", Controllers\PostController::class."::createPost");
Router\Router::post("/tips", Controllers\TipController::class."::createTip");
Router\Router::get("/tips", Controllers\TipController::class."::getTipsByUser");
Router\Router::post("/likes", Controllers\LikeController::class."::addLike");
Router\Router::post("/follow", Controllers\FollowController::class."::addFollower");


Router\Router::listen(function($port){
    echo "<br><h1>Server up and running on port: $port</h1><br>";
});
