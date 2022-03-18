<?php
<<<<<<< HEAD
=======

>>>>>>> origin/master
namespace Router;

require_once __DIR__."/../helpers/autoLoader/autoLoader.php";

<<<<<<< HEAD

class Router
{
    private static $routeTable = [];
    private const METHOD_POST = 'POST';
    private const METHOD_GET = 'GET';
    private const METHOD_DELETE = 'DELETE';
    private const METHOD_PATCH = 'PATCH';


    /**
     * Add a POST route
     * 
     * @param string $path Path to the resource
     * @param function $handler The handler for the resource
     * 
     * @return void
     */
    public static function post(string $path, $handler):void
    {
        self::addCallback(self::METHOD_POST, $path, $handler);
    }

    /**
     * Add a GET route
     * 
     * @param string $path Path to the resource
     * @param function $handler The handler for the resource
     * 
     * @return void
     */
    public static function get(string $path, $handler):void 
    {
        self::addCallback(self::METHOD_GET, $path, $handler);
    }

    /**
     * Add a PATCH route
     * 
     * @param string $path Path to the resource
     * @param function $handler The handler for the resource
     * 
     * @return void
     */
    public static function patch(string $path, $handler):void
    {
        self::addCallback(self::METHOD_PATCH, $path, $handler);
    }

    /**
     * Add a DELETE route
     * 
     * @param string $path Path to the resource
     * @param function $handler The handler for the resource
     * 
     * @return void
     */
    public static function delete(string $path, $handler):void
    {
        self::addCallback(self::METHOD_DELETE, $path, $handler);
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

    private static function addCallback(string $method, string $path, $handler):void
    {
        self::$routeTable[$method.$path] = $handler;
    }

=======
use \Helpers\Request as Request;
use \Helpers\Response as Response;

class Router
{
    private static $routingTable = [];
    private static $METHOD_POST = 'POST';
    private static $METHOD_GET = 'GET';
    private static $METHOD_DELETE = 'DELETE';
    private static $METHOD_PATCH = 'PATCH';
    private static $METHOD_OPTION = 'OPTIONS';


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

    public static function options(string $path, $callback, $secure = false) {
        self::add(self::$METHOD_OPTION, $path, $callback, $secure);
    }
>>>>>>> origin/master

    /**
     * Listening to incoming requests and distributes to appropriate handlers
     * 
<<<<<<< HEAD
     * @return void
     */
    public static function listen()
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
            
        }else
        {
            $callback = self::$routeTable[$requestMethod.$requestPath];
        }
        call_user_func_array($callback, array(new \Helpers\Request(), new \Helpers\Response));     // calls the callback function while passing the Request into it
=======
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
>>>>>>> origin/master
    }
}