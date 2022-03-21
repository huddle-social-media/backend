<?php

namespace Controllers;

require_once __DIR__."/../../helpers/autoLoader/autoLoader.php";

class AuthController
{
    /**
     * Ahuthenticating the refresh token
     * 
     * @param object req The request object
     * @param object res The response object
     * @param object authenticateService Authentication service object
     * @param object authRepo AuthRepo orm layer object
     * 
     * @return object session if the refresh token is authenticated, return valid session
     */
    private static function authRefreshToken($req, $res, $authenticateService)//, $authRepo)
    {
        if(empty($req->refreshToken()))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(401);
            $res->addMessage("Unauthenticated request");
            $res->send();
            exit;
        }

        try {
            $session = \Repository\ORM\ORM::makeSession($req->refreshToken());
            //$session = $authRepo->getSession($req->refreshToken()); +++++++++++++++++++++++++++++++++++++++++++
            
            if($session === null)
            {
                $res->setSuccess(false);
                $res->setHttpStatusCode(401);
                $res->addMessage("Session not exists");
                $res->send();
                exit;
            }

            if(!$authenticateService->isRefreshTokenValid($session))
            {
            // redirect for unsecured login process
                $res->setSuccess(false);
                $res->setHttpStatusCode(401);
                $res->addMessage("Refresh token not valid or expired");
                $res->send();
                exit;
            }
            
            if($authenticateService->isRotateEnable() || ($session->getExpirationTime() - time() <= 86400))
            {
                $refreshToken = $authenticateService->issueRefreshToken();
                $session->setRefreshToken($refreshToken);
                $session->setExpirationTime(time()+30*86400);
            }
            return $session;
        }catch(\PDOException $ex){
            $res->setSuccess(false);
            $res->setHttpStatusCode(500);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }
    }


    /**
     * Ahuthenticating the requested client
     * 
     * @param object res The response object
     * @param object session Corresponding session for refresh token 
     * @param object payload The payload from the access token 
     * @param object authRepo AuthRepo orm layer object
     * 
     * @return void
     */
    private static function authRequestedClient($res, $session, $payload)//, $authRepo)
    {
        if(!property_exists($payload, 'userId') || !is_numeric($payload->userId) || $payload->userId < 0){
            $res->setSuccess(false);
            $res->setHttpStatusCode(401);
            $res->addMessage("credentials not found");
            $res->send();
            exit;
        }

        if($session->getUserId() !== $payload->userId)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(401);
            $res->addMessage("Request not valid");
            $res->send();
            exit;
        }

        try{
            $userStatus = \Repository\ORM\ORM::makeUserStatus($session->getUserId());
            //$userStatus = $authRepo->getUserStatus($session->getUserId()); +++++++++++++++++++++++++++++++++++++++++
            
        } 
        catch(\PDOException $ex)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(500);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }
            
        if($userStatus === null)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(401);
            $res->addMessage("User not available in the platform");
            $res->send();
            exit;
        }

        if($userStatus->banned === true)
        {
            // redirected to banned page
            $res->setSuccess(false);
            $res->setHttpStatusCode(401);
            $res->addMessage("User banned from the platform");
            $res->send();
            exit;
        }

        if($userStatus->status === 'locked')
        {
            // redirect to locked page
            $res->setSuccess(false);
            $res->setHttpStatusCode(401);
            $res->addMessage("User locked from the platform");
            $res->send();
            exit;
        }

        if($userStatus->status === 'unverified')
        {
            // redirect to unverified page
            $res->setSuccess(false);
            $res->setHttpStatusCode(401);
            $res->addMessage("User unverified");
            $res->send();
            exit;
        }

        if($userStatus->login_attempts >= 5)
        {
            // redirect to locked page
            $res->setSuccess(false);
            $res->setHttpStatusCode(401);
            $res->addMessage("Account locked due to login attempts");
            $res->send();
            exit;
        }
    }

    /**
     * Check the validity of the access token
     * 
     * @param object req The request object
     * @param object res The response object
     * @param object authorizationService Authorization service object
     * 
     * @return void
     */
    private static function checkAccessToken($req, $res, $authorizationService)
    {
        if(empty($req->httpAuth()))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Authorization header not found");
            $res->send();
            exit;
        }

        if(!$authorizationService->isTypeValid($req->httpAuth()))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Authorization header not valid");
            $res->send();
            exit;
        }

        if(empty($authorizationService->extractAccessToken($req->httpAuth())))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Access token not found");
            $res->send();
            exit;
        }

        if(!$authorizationService->isAccessTokenVerified($req->httpAuth()))
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Access token not valid");
            $res->send();
            exit;
        }
    }
    
    /**
     * Authorize the access token
     * @param object req The request object
     * @param object res The response object
     * @param object authorizationService Authorization service object
     * 
     * @return object payload The payload from the access token
     */
    private static function authAccessToken($req, $res, $authorizationService)
    {
        try{
            self::checkAccessToken($req, $res, $authorizationService);
            $accessToken = $authorizationService->extractAccessToken($req->httpAuth());
            $payload = $authorizationService->getPayload($accessToken);
            return (object)$payload;
        }catch(\PDOException $ex){
            $res->setSuccess(false);
            $res->setHttpStatusCode(500);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }catch(\Exception $ex) // for catching JWT exception, but need proper exeception handling
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }
    }

    /**
     * Auth function for authorzing and authenticating secured requests
     * @param object req The request object
     * @param object res The response object
     * @param function next The function to call next
     * 
     * @return void
     */
    public static function auth($req, $res, $next)
    {
        try {
            $authenticateService = new \Services\Authentication();
            $authorizationService = new \Services\Authorization();
            $session = self::authRefreshToken($req, $res, $authenticateService);
            $payload = self::authAccessToken($req, $res, $authorizationService);
            self::authRequestedClient($res, $session, $payload);
            call_user_func_array($next, [$req, $res, $payload->userId]);
        }catch(\PDOException $ex){
            $res->setSuccess(false);
            $res->setHttpStatusCode(500);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }catch(\Exception $ex) {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }
    }

    /**
     * Handling silent authentication process
     * @param object req The request object
     * @param object res The response object
     * 
     * @return void
     */
    public static function silentAuthenticate($req, $res)
    {
        try{
            $authorizationService = new \Services\Authorization();
            $authenticateService = new \Services\Authentication();
            //$authRepo = new \Repository\AuthRepo(); +++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $session = self::authRefreshToken($req, $res, $authenticateService);
            self::checkAccessToken($req, $res, $authorizationService);
            $segments = explode('.', $authorizationService->extractAccessToken($req->httpAuth()));
            $header = (array)json_decode(\Helpers\JWT::urlSafeBase64Decode($segments[0]));
            $payload = (array)json_decode(\Helpers\JWT::urlSafeBase64Decode($segments[1]));
            self::authRequestedClient($res, $session, (object)$payload);
            $payload['exp'] = time()+ 60*60;
            $newAccessToken = \Helpers\JWT::encode($payload, $authorizationService->getSecretKey(), $header['alg'], $header);
            // send the access token and the refresh token
            // for debug purpose i set the domain for root
            $arr_cookie_options = array (
                'expires' => time() +30*86400,
                'path' => '/',
                'domain' => null, // leading dot for compatibility or use subdomain
                'secure' => true,     // or false
                'httponly' => true,    // or false
                'samesite' => 'None' // None || Lax  || Strict
                );
            setcookie('refreshToken', $session->getRefreshToken(), $arr_cookie_options);
            $res->setSuccess(true);
            $res->setHttpStatusCode(200);
            $res->setData(['accessToken' => $newAccessToken]);
            $res->addMessage("Access grant");
            \Repository\ORM\ORM::updateObject($session,['session_id']);
            //$session->update(); ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            $res->send();
            exit;
        }catch(\PDOException $ex){
            $res->setSuccess(false);
            $res->setHttpStatusCode(500);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }catch(\Exception $ex) {
            $res->setSuccess(false);
            $res->setHttpStatusCode(401);
            $res->addMessage($ex->getMessage());
            $res->send();
            exit;
        }
    }
}