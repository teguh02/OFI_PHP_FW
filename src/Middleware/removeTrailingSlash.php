<?php

namespace ofi\ofi_php_framework\Middleware;

class removeTrailingSlash {

    public static function handle()
    {
        if (substr(self::getCurrentRequest(), -1) === '/') {
            return header("Location: " . rtrim(self::getCurrentRequest(), '/'));
        }
    }

    /**
     * To get current request
     */
    private static function getCurrentRequest()
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $current_request = str_replace(PROJECTURL, '', $actual_link);
        return $current_request;
    }

}