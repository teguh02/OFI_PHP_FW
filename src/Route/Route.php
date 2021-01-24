<?php

namespace ofi\ofi_php_framework\Route;
use ofi\ofi_php_framework\Route\Interfaces\routeInterface;

class Route implements routeInterface { 

    private static $routeArray = [];
    private static $autoRoute = null;

    /**
     * Route get method
     */
    public static function get(String $url, $callback = [], Array $options =[])
    {
        $func_get_args = func_get_args();
        $func_get_args['3'] = 'GET';
        $func_get_args['5'] = $options;
        array_push(self::$routeArray, $func_get_args);
        return true;
    }

    /**
     * Route post method
     */
    public static function post(String $url, $callback = [], Array $options =[])
    {
        $func_get_args = func_get_args();
        $func_get_args['3'] = 'POST';
        $func_get_args['5'] = $options;
        array_push(self::$routeArray, $func_get_args);
        return true;
    }

    /**
     * Route put method
     */
    public static function put(String $url, $callback = [], Array $options =[])
    {
        $func_get_args = func_get_args();
        $func_get_args[]['3'] = 'PUT';
        $func_get_args['5'] = $options;
        array_push(self::$routeArray, $func_get_args);
        return true;
    }

    /**
     * Route delete method
     */
    public static function delete(String $url, $callback = [], Array $options =[])
    {
        $func_get_args = func_get_args();
        $func_get_args['3'] = 'DELETE';
        $func_get_args['5'] = $options;
        array_push(self::$routeArray, $func_get_args);
        return true;
    }

    /**
     * Route any method
     */
    public static function any(String $url, $callback = [], Array $options =[])
    {
        $func_get_args = func_get_args();
        $func_get_args['3'] = 'Any';
        $func_get_args['5'] = $options;
        array_push(self::$routeArray, $func_get_args);
        return true;
    }

    /**
     * To set auto routing mode
     */
    public static function auto(Bool $status = true)
    {
        return self::$autoRoute = $status;
    }

    /**
     * To get route list
     */
    public function getRouteArray(): Array
    {
        return (array) self::$routeArray;
    }

    /**
     * Get Auto route status
     */
    public function getAutoRoute()
    {
        return self::$autoRoute;
    }

    /**
     * Generate route url
     */
    public function generatePath(String $routeName)
    {
        $routeArray = $this->getRouteArray();
        $routeName = trim($routeName, '/');

        for ($i=0; $i < count($routeArray) ; $i++) { 
            if (isset($routeArray[$i][5]['name']) && $routeArray[$i][5]['name'] === $routeName) {
                return PROJECTURL . '/' . $routeArray[$i][0];
            }
        }

        return PROJECTURL . '/' . $routeName;
    }
}