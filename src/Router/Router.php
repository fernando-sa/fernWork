<?php

namespace fernandoSa\Router;

class Router
{
    private $appRoutes;
    private $method;
    private $path;

    public function __construct(string $path, string $method)
    {
        $this->appRoutes = new RoutesDictionary;
        $this->path = $path;
        $this->method = $method;
    }

    public function request(string $method, string $path, $callback) : void
    {
        $this->appRoutes->add($method, $path, $callback);
    }

    public function get(string $path, $callback) : void
    {
        $this->request('GET', $path, $callback);
    }

    public function post(string $path, $callback) : void
    {
        $this->request('POST', $path, $callback);
    }

    public function run() : array
    {
        $routes = $this->appRoutes->findByMethod($this->method);
        
        foreach ($routes as $route) {
            $result = $this->checkUrl($route['path'], $this->path);
            $value = $route['callback'];
            if ($result['result']) {
                break;
            }
        }

        ! $result['result']
            ? $callback = null
            : $callback = $value;

        return [
            'parameters' => $result['namedParameters'],
            'callback' => $callback,
        ];
    }


    // Function to match incoming route with route in our dictionary and separate paramaters in URL
    private function checkUrl(string $toFind, $subject) : array
    {

        preg_match_all('/\{([^\}]*)\}/', $toFind, $variables);


        $urlRegex = str_replace('/', '\/', $toFind);

        foreach ($variables[1] as $k => $variable) {
            $as = explode(':', $variable);
            $replacement = $as[1] ?? '([a-zA-Z0-9\-\_\ ]+)';
            $urlRegex = str_replace($variables[$k], $replacement, $urlRegex);
        }
        $urlRegex = preg_replace('/{([a-zA-Z]+)}/', '([a-zA-Z0-9+])', $urlRegex);
        $result = preg_match('/^' . $urlRegex . '$/', $subject, $parameters);
        
        // Go through every parameter to assign it to corret associative index
        for ($i=0; $i < count($variables[0]); $i++) {
            $variables[0][$i] = str_replace("{", "", $variables[0][$i]);
            $variables[0][$i] = str_replace("}", "", $variables[0][$i]);

            // Paramaeters has to be +1 cause first index is the entire url regex.
            // Cause = preg_match questionable API
            $namedParameters[$variables[0][$i]] = $parameters[$i + 1];
        }


        return compact('result', 'namedParameters');
    }
}
