<?php

namespace Helpers;

class Request
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
    private string $refreshToken;
    private string $httpAuth;

    public function __construct()
    {
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

        // set request uri
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

        $this->setRefreshToken();
        $this->setHttpAuth();
    }

    /**
     * Setting the request body using content string
     * if request content exists, otherwise setting body to null
     *
     * @return void
     */
    private function setBody()
    {
        if($data = json_decode(file_get_contents("php://input")))
        {
            $this->body = $data;
            return;
        }
        $this->body = (object)[];
    }

    /**
     * Setting request parameters
     *
     * ex: /posts/852/comments/14
     * params = [1=>852, 2=>14]
     *
     * @return void
     */
    private function setParams()
    {
        $matches = [];
        preg_match_all('/[\d]+/', parse_url($_SERVER['REQUEST_URI'])['path'], $matches);
        $this->params = $matches[0];
    }

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
        $matches = [];
        if(isset($_SERVER['HTTP_AUTHORIZATION']))
        {
            preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches);
        }
        if(!isset($_SERVER['HTTP_AUTHORIZATION']) || $matches[1] === "null")
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
    {
        return $this->body;
    }

    /**
     * get cookies
     *
     * @return array cookies
     */
    public function cookies()
    {
        return $this->cookies;
    }

    /**
     * get hostname
     *
     * @return string hostname
     */
    public function hostname()
    {
        return $this->hostname;
    }

    /**
     * get ip from the remote server
     *
     * @return string ip
     */
    public function ip()
    {
        return $this->ip;
    }

    /**
     * get originalUri
     * ex: returning /posts?search=1
     *
     * @return string originalUri
     */
    public function originalUri()
    {
        return $this->originalUri;
    }

    /**
     * get params
     *
     * @return array params
     */
    public function params()
    {
        return $this->params;
    }

    /**
     * get path
     * ex: /posts/2
     *
     * @return string path
     */
    public function path()
    {
        return $this->path;
    }

    /**
     * get request protocol
     * ex:http/1.1
     *
     * @return string protocol
     */
    public function protocol()
    {
        return $this->protocol;
    }

    /**
     * get query strings
     *
     * @return array query
     */
    public function query()
    {
        return $this->query;
    }

    /**
     * get request method
     *
     * @return string method
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * get headers
     *
     * @return array headers
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * get form data that come through POST
     *
     * @return array form
     */
    public function form()
    {
        return $this->form;
    }

    /**
     * get contentType
     * ex: application/json
     *
     * @return string contentType
     */
    public function contentType()
    {
        return $this->contentType;
    }

    public function refreshToken()
    {
        return $this->refreshToken;
    }

    public function httpAuth()
    {
        return $this->httpAuth;
    }
}
