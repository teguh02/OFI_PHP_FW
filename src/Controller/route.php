<?php 

namespace ofi\ofi_php_framework\Controller;
use Exception;
use ofi\ofi_php_framework\Controller\getRoute;

class route {

    protected static $routeStatus;
    protected static $arrayRoute = [];
    private static $_instance = null;
    protected static $index = 0;

    use getRoute;

    public function __construct()
    {
        self::$routeStatus = false;
    }

    public static function start()
    {
        if(self::$_instance == null) {
            self::$_instance = new self();
        } 

        self::$routeStatus = true;  
        return self::$_instance;
    }

    public function route($index)
    {
        if(self::$routeStatus) {
            self::$index = $index;
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    /**
     * Define route middleware
     */

    public function middleware($value)
    {
        if (self::$routeStatus) {

            $explode = explode('@', $value);

            // cek file apakah tersedia
            $path = str_replace('\\', '/', BASEURL) . '/App/Middleware/' . str_replace('\\', '/', $explode[0]) . '.php';

            if(!file_exists($path)) {
                throw new Exception("File App/Middleware/" . str_replace('\\', '/', $explode[0]) . '.php not found!', 1);
            }

            self::$arrayRoute[self::$index]['middleware'] = $value; 
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    /**
     * URL type view or controller
     */

    public function type($value)
    {
        if (self::$routeStatus) {
            self::$arrayRoute[self::$index]['type'] = $value; 
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    /**
     * Your url request
     */

    public function url($value)
    {
        if (self::$routeStatus) {
            self::$arrayRoute[self::$index]['url'] = str_replace('/', '', $value);
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    /**
     * Destination from your route
     */

    public function to($value)
    {
        if (self::$routeStatus) {

            if(self::$arrayRoute[self::$index]['type'] == 'controller') {
                if (strpos($value, "@") != true) {
                    throw new Exception("Route : ". $value ." error! You must define a method in your route" . '. For example ' . $value . '@example', 1);
                }

                $explode = explode('@', $value);
                $path = str_replace('\\', '/', BASEURL) . '/App/Controllers/' . str_replace('\\', '/', $explode[0]) . '.php';

                // Cek apakah file controller tersedia?
                if(!file_exists($path)) {
                    throw new Exception("File " . $explode[0] . '.php not found!', 1);
                }
            } else {
                // Cek apakah file view tersedia?
                $path = str_replace('\\', '/', BASEURL) . '/resources/views/' . $value . '.ofi.php'; 
                if(!file_exists($path)) {
                    throw new Exception("File /resources/views/" . $value . '.ofi.php not found!', 404);
                }
            }

            self::$arrayRoute[self::$index]['to'] = $value;
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    /**
     * Define route method
     * POST, GET
     */

    public function method($value)
    {
        if (self::$routeStatus) {
            self::$arrayRoute[self::$index]['method'] = strtoupper($value);
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    /**
     * Define route name
     */

    public function name($name)
    {
        if (self::$routeStatus) {
            self::$arrayRoute[self::$index]['name'] = $name;
            return self::$_instance;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }

    public function end()
    {
        if (self::$routeStatus) {
            return self::$arrayRoute;
        } else {
            throw new Exception("Invalid route code structure", 1);
        }
    }
}