<?php 

namespace ofi\ofi_php_framework\Route\Interfaces;

interface routeInterface {

    public static function get(String $url, $callback = [], Array $options = []);
    public static function post(String $url, $callback = [], Array $options = []);
    public static function delete(String $url, $callback = [], Array $options = []);
    public static function put(String $url, $callback = [], Array $options = []);
    public static function any(String $url, $callback = [], Array $options = []);
    public static function auto(Bool $status = true);    
    public function getRouteArray() : Array;

}