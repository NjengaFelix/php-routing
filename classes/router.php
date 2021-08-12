<?php

class Router {
    private $handlers;
    private const METHOD_GET = 'GET';
    private const METHOD_POST = 'POST';
    private $notFoundHandler;

    public function get($path, $handler)
    {
        $this->addHandler(self::METHOD_GET, $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addHandler(self::METHOD_POST, $path, $handler);
    }

    private function addHandler($method, $path, $handler) {
        $this->handlers[$method.$path] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
        ];
    }

    public function addNotFoundHandler($handler)
    {
        $this->notFoundHandler = $handler;
    }

    public function run()
    {
        // Use parse_url to get an associate array of the requested path
        // path i.e. if request_URI acadcomplaints/about?name=paul
        //requestUri['path'] = acadcomplaints/about without the parameters
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $method = $_SERVER['REQUEST_METHOD'];
        

        $callback = null;
        foreach ($this->handlers as $handler) {
            if($handler['path'] === $requestPath && $method === $handler['method']) {
                $callback = $handler['handler'];
            }
        }
        
        // var_dump($callback);

        if(is_string($callback)) {
            $parts = explode('::',$callback);
            if(is_array($parts)) {
                $className = array_shift($parts);
                $handler = new $className;

                $method = array_shift($parts);
                $callback = [$handler, $method];
            }
        }

        if(!$callback) {
            if(!empty($this->notFoundHandler)) {
                $callback = $this->notFoundHandler;
            }
        }

        //define the callbacks and add arguments
        //either get or post
        call_user_func_array($callback,[
            array_merge($_GET, $_POST)
        ]);
    }


}