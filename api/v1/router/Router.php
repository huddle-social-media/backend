<?php
namespace Router;

require_once __DIR__."/../helpers/autoLoader/autoLoader.php";


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


    /**
     * Listening to incoming requests and distributes to appropriate handlers
     * 
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
    }
}