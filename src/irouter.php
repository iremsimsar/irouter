<?php

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

        // Mevcut istek yöntemini al
        $path = $parsed_url['path'] ?? '/'; //yoksa yanındakini ver
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {

            // Eşleşen dizeye temel yol ekle
            $route['expression'] = $route['expression'] . '$';

            //burada preg_match arama yaapar
            // Yol eşleşmesini kontrol eder
            if (preg_match('#' . $route['expression'] . '#', $path, $matches)) {

                // Yöntem eşleşmesini kontrol edin
                if (strtolower($method) == strtolower($route['method'])) {
                    //burada aldıgı diziden birisini çeker
                    call_user_func_array($route['function'], $matches);
                    break;
                }
            }
        }
    }
}
