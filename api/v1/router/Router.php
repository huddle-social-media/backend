<?php

namespace Router;

require_once __DIR__."/../helpers/Request/Request.php";
require_once __DIR__."/../helpers/Response/Response.php";
require_once __DIR__."/../auth/controllers/AuthController.php";

use \Helpers\Request as Request;
use \Helpers\Response as Response;

class Router
{
    private static $routingTable = [];
    private static $METHOD_POST = 'POST';
    private static $METHOD_GET = 'GET';
    private static $METHOD_DELETE = 'DELETE';
    private static $METHOD_PATCH = 'PATCH';


    /**
     * Registering POST routes
     * 
     * @param string path The route
     * @param function callback The handler for registered route
     * 
     * return void
     */
    public static function post(string $path, $callback, $secure = true)
    {
        self::add(self::$METHOD_POST, $path, $callback, $secure);
    }

    /**
     * Registering GET routes
     * 
     * @param string path The route
     * @param function callback The handler for registered route
     * 
     * return void
     */
    public static function get(string $path, $callback, $secure = true) 
    {
        self::add(self::$METHOD_GET, $path, $callback, $secure);
    }

    /**
     * Registering PATCH routes
     * 
     * @param string path The route
     * @param function callback The handler for registered route
     * 
     * return void
     */
    public static function patch(string $path, $callback, $secure = true) 
    {
        self::add(self::$METHOD_PATCH, $path, $callback, $secure);
    }

    /**
     * Registering DELETE routes
     * 
     * @param string path The route
     * @param function callback The handler for registered route
     * 
     * return void
     */
    public static function delete(string $path, $callback, $secure = true) 
    {
        self::add(self::$METHOD_DELETE, $path, $callback, $secure);
    }

    /**
     * Registering provided route to the routing table
     * 
     * @param string method The method
     * @param string path The route to register
     * @param function callback The handler
     * 
     * return void
     */
    private static function add(string $method, string $path, $callback, $secure) 
    {
        self::$routingTable[$method.$path] = (object)[
            "callback" => $callback,
            "secure" => $secure
        ];
    }


    /**
     * Listening to incoming requests and distributes to appropriate handlers
     * 
     * @param function callback The callback
     * 
     * return void
     */
    public static function listen($callback)
    {
        $requestedRoute = parse_url($_SERVER['REQUEST_URI'])['path'];
        $generalRoute = preg_replace('/[\d]+/', ':id', $requestedRoute);
        $generalRoute = $_SERVER['REQUEST_METHOD'].$generalRoute;
        if(!array_key_exists($generalRoute, self::$routingTable))
        {
            echo "<h1>404</h1>";
            return;
        }
        $route = self::$routingTable[$generalRoute];
        $req = new Request();
        $res = new Response();
        if($route->secure === true)
            \Controllers\AuthController::auth($req, $res, $route->callback);
        else
            call_user_func_array($route->callback, [$req, $res]);
        $callback($_SERVER['SERVER_PORT']);
    }
}