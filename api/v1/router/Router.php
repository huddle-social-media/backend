<?php

namespace Router;

require_once __DIR__."/../helpers/autoLoader/autoLoader.php";

use \Helpers\Request as Request;
use \Helpers\Response as Response;

class Router
{
    private static $routeTable = [];
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';
    private const METHOD_DELETE = 'DELETE';
    private const METHOD_PATCH = 'PATCH';
    private const METHOD_OPTIONS = 'OPTIONs';


    /**
 

    /**
     * Add a POST route
     * 
     * @param string $path Path to the resource
     * @param function $handler The handler for the resource
     * 
     * @return void
     */
    public static function post(string $path, $handler, $secure = true):void
    {
        self::addCallback(self::METHOD_POST, $path, $handler, $secure);
    }

    /**
     * Add a GET route
     * 
     * @param string $path Path to the resource
     * @param function $handler The handler for the resource
     * 
     * @return void
     */
    public static function get(string $path, $handler, $secure = true):void 
    {
        self::addCallback(self::METHOD_GET, $path, $handler, $secure);
    }

    /**
     * Add a PATCH route
     * 
     * @param string $path Path to the resource
     * @param function $handler The handler for the resource
     * 
     * @return void
     */
    public static function patch(string $path, $handler, $secure = true):void
    {
        self::addCallback(self::METHOD_PATCH, $path, $handler, $secure);
    }

    /**
     * Add a DELETE route
     * 
     * @param string $path Path to the resource
     * @param function $handler The handler for the resource
     * 
     * @return void
     */
    public static function delete(string $path, $handler, $secure = true):void
    {
        self::addCallback(self::METHOD_DELETE, $path, $handler, $secure);
    }

    /**
     * Registering OPTIONS routes
     * 
     * @param string path The route
     * @param function callback The handler for registered route
     * 
     * @return void
     */
    public static function options($path, $handler, $secure = true):void
    {
        self::addCallback(self::METHOD_OPTIONS, $path, $handler, $secure);
    }


    /**
     * Add a route to the routeTable
     * 
     * @param string $method The request method
     * @param string $path The path to the resource
     * @param function $handler The handler for the resource
     * 
     * @return void
     */

    private static function addCallback(string $method, string $path, $handler, $secure):void
    {
        self::$routeTable[$method.$path] = (object)[
            "callback" => $handler,
            "secure" => $secure
        ];
    }


    /**
     * Listens to incoming requests and distributes to appropriate handlers
     * 
     * @return void
     */
    public static function listen($callback)
    {
        $requestURL = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestURL['path'];                                                 // get path from URL
        $requestPath = preg_replace('/[\d]+/', ':id', $requestPath);                        // replace actual id with string 'id'
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if(!array_key_exists($requestMethod.$requestPath, self::$routeTable))
        {
            $response = new \Helpers\Response();
            $response->addMessage("Resource not found");
            $response->setHttpStatusCode(404);
            $response->setSuccess(false);
            $response->send();
            exit;
            
        }
        
        $route = self::$routeTable[$requestMethod.$requestPath];
        
        $req = new Request();
        $res = new Response();
        if($route->secure === true)
            \Controllers\AuthController::auth($req, $res, $route->callback);
        else
            call_user_func_array($route->callback, [$req, $res]);
        $callback($_SERVER['SERVER_PORT']);
   }
}