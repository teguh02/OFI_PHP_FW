<?php 

namespace ofi\ofi_php_framework\Controller;

use Exception;

trait getRoute {

    /**
     * Get All route data as array
     */

    public static function getAsArray()
    {
        return (Array) self::$arrayRoute;
    }

    /**
     * Get Current route name
     */

    public static function getName()
    {
        $Current_url = trim($_SERVER['REQUEST_URI'], '/');
        $route = self::getAsArray();

        for ($i=0; $i < count($route) ; $i++) { 
            if(strtolower($route[$i]['url']) == $Current_url) {
                if(isset($route[$i]['name'])) {
                    return $route[$i]['name'];
                } else {
                    return null;
                }
            }
        }
    }

    public function Generate($name = '')
    {
        if(empty($name)) {
            throw new Exception("You must define route name!");
        }

        $route = self::getAsArray();

        for ($i=0; $i < count($route) ; $i++) { 
            if(strtolower($route[$i]['name']) == $name) {
                return PROJECTURL . '/' . $route[$i]['name']; 
            }
        }

        return false;
    }

    /**
     * Get All route data as object
     */

    public static function getAsObject()
    {
        return (Object) self::$arrayRoute;
    }
}