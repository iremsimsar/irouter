<?php

namespace IRouter;

class Route
{

    private static $routes = [];

    public static function add($expression, $function, $method = 'get')
    {

        self::$routes[] = [
            'expression' => $expression,
            'function' => $function,
            'method' => $method
        ];
    }
    public static function run()
    {

        $parsed_url = parse_url($_SERVER['REQUEST_URI']);

        $path = $parsed_url['path'] ?? '/'; 
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {

            $route['expression'] = $route['expression'] . '$';

            if (preg_match('#' . $route['expression'] . '#', $path, $matches)) {

                if (strtolower($method) == strtolower($route['method'])) {

                    call_user_func_array($route['function'], $matches);
                    break;
                }
            }
        }
    }
}
