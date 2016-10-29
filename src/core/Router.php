<?php

namespace Fagoc\Core;

class Router
{
    private $method = 'GET';
    private $uri = '';
    private $url = '';

    /**
    * routes[
    *  'GET' => [
    *    '/' => 'faço isso',
    *    '/exercicio/6' => 'carregar o arquivo do exercicio 6'
    *    ]
    * ]
    */
    private $routes = [];

    public function __construct()
    {
        $this->parseRequest();
    }

    private function parseRequest()
    {
        $self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '';
        $peaces = explode('/', $self);
        array_pop($peaces);

        $start = implode('/', $peaces);
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $search = '/' . preg_quote($start, '/') . '/';
        $uri = preg_replace($search, '', $request_uri, 1);

        $this->uri = $uri;
        $this->method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : $this->method;
        $this->url = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $start : $this->url;
    }

    public function __call($name, $arguments)
    {
        $method = strtoupper($name);
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }
        $peaces = explode('/', $arguments[0]);
        foreach ($peaces as $key => $value) {
            if (strpos($value, ':') === 0) {
                $peaces[$key] = '(\w+)';
            }
        }
        $pattern = str_replace('/', '\/', implode('/', $peaces));
        $route = '/^' . $pattern . '$/';
        $callback = $arguments[1];

        $this->routes[$method][$route] = $callback;
    }

    public function run()
    {
        $routes = $this->routes[$this->method];
        foreach($routes as $route => $callback) {
            if (preg_match($route, $this->uri, $params)) {
                array_shift($params);
                return call_user_func_array($callback, array_values($params));
            }
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getUrl()
    {
        return $this->url;
    }

}
