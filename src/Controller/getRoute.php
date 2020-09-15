<?php 

namespace ofi\ofi_php_framework\Controller;

trait getRoute {

    /**
     * Get All route data as array
     */

    public static function getAsArray()
    {
        return (Array) self::$arrayRoute;
    }

    /**
     * Get All route data as object
     */

    public static function getAsObject()
    {
        return (Object) self::$arrayRoute;
    }
}