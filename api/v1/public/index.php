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

Router\Router::get("/events/all_events", Controllers\EventController::class."::allEvents");
Router\Router::options("/events/all_events", Controllers\EventController::class."::CROS");

Router\Router::get("/events/attending_events", Controllers\EventController::class."::attendingEvents");
Router\Router::options("/events/attending_events", Controllers\EventController::class."::CROS");

Router\Router::patch("/events/leave_event", Controllers\EventController::class."::leaveEvent");
Router\Router::options("/events/leave_event", Controllers\EventController::class."::CROS");

Router\Router::patch("/events/attend_event", Controllers\EventController::class."::attendEvent");
Router\Router::options("/events/attend_event", Controllers\EventController::class."::CROS");

Router\Router::get("/issues/issues_on_user", Controllers\IssueController::class."::issuesOnUser");
Router\Router::options("/issues/issues_on_user", Controllers\IssueController::class."::CROS");

Router\Router::get("/issues/issues_accepted_by_user", Controllers\IssueController::class."::issuesAcceptedByUser");
Router\Router::options("/issues/issues_accepted_by_user", Controllers\IssueController::class."::CROS");

Router\Router::post("/issues/accept_issue", Controllers\IssueController::class."::acceptIssue");
Router\Router::options("/issues/accept_issue", Controllers\IssueController::class."::CROS");


Router\Router::patch("/issues/mark_issue_chat_read", Controllers\IssueController::class."::markIssueChatAsRead");
Router\Router::options("/issues/mark_issue_chat_read", Controllers\IssueController::class."::CROS");


Router\Router::post("/issues/send_issue_chat", Controllers\IssueController::class."::sendIssueChat");
Router\Router::options("/issues/send_issue_chat", Controllers\IssueController::class."::CROS");

Router\Router::post("/issues/create_issue", Controllers\IssueController::class."::createIssue");
Router\Router::options("/issues/create_issue", Controllers\IssueController::class."::CROS");

Router\Router::post("/issues/create_event", Controllers\EventController::class."::createEvent");
Router\Router::options("/issues/create_event", Controllers\EventController::class."::CROS");

Router\Router::get("/issues/unsent_issue_chat", Controllers\IssueController::class."::getUnSentIssueChat");
Router\Router::options("/issues/unsent_issue_chat", Controllers\IssueController::class."::CROS");

Router\Router::listen(function($port){
    echo "<br><h1>Server up and running on port: $port</h1><br>";
});
