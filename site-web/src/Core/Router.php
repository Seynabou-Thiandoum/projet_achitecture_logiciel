<?php
namespace App\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, array $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => $path,
            'handler' => $handler,
        ];
    }

    public function get(string $path, array $handler): void  { $this->add('GET',  $path, $handler); }
    public function post(string $path, array $handler): void { $this->add('POST', $path, $handler); }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?? '/';
        $base = parse_url(BASE_URL, PHP_URL_PATH) ?? '';
        if ($base && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        if ($uri === '' || $uri === false) { $uri = '/'; }

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) { continue; }
            $pattern = '#^' . preg_replace('#\{([\w]+)\}#', '(?P<$1>[^/]+)', $route['path']) . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                [$class, $action] = $route['handler'];
                (new $class())->{$action}(...array_values($params));
                return;
            }
        }
        http_response_code(404);
        echo '<h1>404 - Page non trouvee</h1>';
    }
}
