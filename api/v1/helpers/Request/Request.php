<?php

namespace Helpers;

<<<<<<< HEAD
class Request 
=======
class Request
>>>>>>> origin/master
{
    /**
     * defining private variables
     */
    private mixed $body;
    private array $cookies;
    private string $hostname;
    private string $ip;
    private string $originalUri;
    private array $params;
    private string $path;
    private string $protocol;
    private array $query;
    private string $method;
    private array $headers;
    private array $form;
    private string $contentType;
<<<<<<< HEAD

    public function __construct()
    {   
=======
    private string $refreshToken;
    private string $httpAuth;

    public function __construct()
    {
>>>>>>> origin/master
        // set the body
        $this->setBody();

        // set cookies
        $this->cookies = $_COOKIE;

        // set hostname
        $this->hostname = $_SERVER['HTTP_HOST'];

        // set ip
        $this->ip = $_SERVER['REMOTE_ADDR'];

        // set original uri
        $this->originalUri = $_SERVER['REQUEST_URI'];

        // set params
        $this->setParams();

<<<<<<< HEAD
        // set request uri 
=======
        // set request uri
>>>>>>> origin/master
        $this->path = parse_url($_SERVER['REQUEST_URI'])['path'];

        // set protocol
        $this->protocol = $_SERVER['SERVER_PROTOCOL'];

        // set query if $_GET not empty
        (!empty($_GET)) ? $this->query = $_GET :  $this->query = [];

        // set form if $_POST not empty
        (!empty($_POST)) ? $this->query = $_POST :  $this->form = [];

        // set contentType if its define in headers
        (array_key_exists('CONTENT_TYPE', $_SERVER)) ? $this->contentType = $_SERVER['CONTENT_TYPE'] : $this->contentType = '';

        // set request method ex: GET, POST, PATCH...
        $this->method = $_SERVER['REQUEST_METHOD'];

        // set headers
        $this->headers = getallheaders();
<<<<<<< HEAD
=======

        $this->setRefreshToken();
        $this->setHttpAuth();
>>>>>>> origin/master
    }

    /**
     * Setting the request body using content string
     * if request content exists, otherwise setting body to null
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return void
     */
    private function setBody()
    {
        if($data = json_decode(file_get_contents("php://input")))
        {
            $this->body = $data;
            return;
        }
<<<<<<< HEAD
        $this->body = null;
    }

    public function validateRequest($res)
    {
        if($this->contentType() !== "application/json")
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Content type invalid");
            $res->send();
            exit;
        }

        if(($body = $this->body()) === false)
        {
            $res->setSuccess(false);
            $res->setHttpStatusCode(400);
            $res->addMessage("Request body contain invalid JSON");
            $res->send();
            exit;
        }

        return $body;
=======
        $this->body = (object)[];
>>>>>>> origin/master
    }

    /**
     * Setting request parameters
<<<<<<< HEAD
     * 
     * ex: /posts/852/comments/14
     * params = [1=>852, 2=>14]
     * 
=======
     *
     * ex: /posts/852/comments/14
     * params = [1=>852, 2=>14]
     *
>>>>>>> origin/master
     * @return void
     */
    private function setParams()
    {
        $matches = [];
        preg_match_all('/[\d]+/', parse_url($_SERVER['REQUEST_URI'])['path'], $matches);
        $this->params = $matches[0];
    }

<<<<<<< HEAD
    /**
     * get body
     * 
     * @return array/object body
     */
    public function body() 
=======
    private function setRefreshToken()
    {
        if(!isset($_COOKIE['refreshToken']))
        {
            $this->refreshToken = '';
            return;
        }
        $this->refreshToken = $_COOKIE['refreshToken'];
    }

    private function setHttpAuth()
    {
        if(!isset($_SERVER['HTTP_AUTHORIZATION']))
        {
            $this->httpAuth = '';
            return;
        }
        $this->httpAuth = $_SERVER['HTTP_AUTHORIZATION'];

    }

    /**
     * get body
     *
     * @return array/object body
     */
    public function body()
>>>>>>> origin/master
    {
        return $this->body;
    }

    /**
     * get cookies
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return array cookies
     */
    public function cookies()
    {
        return $this->cookies;
    }

    /**
     * get hostname
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return string hostname
     */
    public function hostname()
    {
        return $this->hostname;
    }

    /**
     * get ip from the remote server
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return string ip
     */
    public function ip()
    {
        return $this->ip;
    }

    /**
     * get originalUri
     * ex: returning /posts?search=1
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return string originalUri
     */
    public function originalUri()
    {
        return $this->originalUri;
    }

    /**
     * get params
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return array params
     */
    public function params()
    {
        return $this->params;
    }

    /**
     * get path
     * ex: /posts/2
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return string path
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * get request protocol
     * ex:http/1.1
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return string protocol
     */
    public function protocol()
    {
        return $this->protocol;
    }

    /**
     * get query strings
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return array query
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * get request method
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return string method
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * get headers
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return array headers
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * get form data that come through POST
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return array form
     */
    public function form()
    {
        return $this->form;
    }

    /**
     * get contentType
     * ex: application/json
<<<<<<< HEAD
     * 
=======
     *
>>>>>>> origin/master
     * @return string contentType
     */
    public function contentType()
    {
        return $this->contentType;
    }
<<<<<<< HEAD
=======

    public function refreshToken()
    {
        return $this->refreshToken;
    }

    public function httpAuth()
    {
        return $this->httpAuth;
    }
>>>>>>> origin/master
}
