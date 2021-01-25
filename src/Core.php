<?php

namespace ofi\ofi_php_framework;

session_start();
use App\provider\event;
use ofi\ofi_php_framework\Controller;
use Exception;
use App\Middleware\kernel as middlewareKernel;
use Closure;
use ofi\ofi_php_framework\Route\Route;
use ofi\ofi_php_framework\Support\CSRF;
use ofi\ofi_php_framework\Support\errorPage;

global $config;
require_once __DIR__ . '/Support/kint.phar';
require_once 'config.php';
require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once BASEURL . '/route/web.php';

/**
 * Add CSRF input hidden to your form
 */

define('CSRF', CSRF::getHiddenInputString());

/**
 * Get CSRF Input Name
 * default CSRF input name is 
 * OFI_FW_CSRF
 */

define('CSRFNAME', CSRF::getTokenName());

/**
 * Get CSRF only Value
 */

define('CSRFVALUE', CSRF::getToken());

class Core extends event
{
    use errorPage;

    public function __construct()
    {
        $middleware = new middlewareKernel();
        $middleware->register();

        if(!defined('BASEURL') || !defined('UPLOADPATH')) {
            throw new Exception("Something went wrong, please check BASEURL or UPLOADPATH configuration!");
        }

        switch (ENVIRONMENT) {
            case 'development':
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
        
                $whoops = new \Whoops\Run();
                $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
                $whoops->register();
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
                break;
        
            case 'production':
                
                error_reporting(0);
                ini_set('display_errors', 0);
                try {
                    $this->run();
                } catch (\Throwable $th) {
                    $controller = new Controller();
                    $controller->error500('Something went wrong, please contact this sites admin');
                    die();
                }

                break;

            default:
                error_reporting(0);
                $controller = new Controller();
                $controller->error500('Something went wrong, please set your application environment');
                break;
        }
    }

    /**
     * Method Run
     * This method will be run for the
     * first time while the program is running.
     */
    public function run()
    {
        // Harus yang paling atas
        $this->CSRF();
        $this->whenRun();
        $this->matchRoute($this->getCurrentRequest());
    }

    /**
     * To activate and validate CSRF Protection
     * in our system
     * Only POST will get validate by our system
     */

    public function CSRF()
    {
        // Detect HTTP Method
        $request_method = $_SERVER['REQUEST_METHOD'];

        // Get Request URL
        $request_url = rtrim($this->getCurrentRequest(), '/');

        if ($request_method === "POST" || $request_method === 'PUT') {

            // generic POST data
            $getToken = null;
            if (isset($_POST)) {
                $getToken = $_POST;
            } else {
                $getToken = $_REQUEST;
            }

            // Cari apakah url pada request_url ada dalam daftar bypassCSRF?
            include BASEURL . 'App/Middleware/bypassCSRF.php';

            // Jika tidak ditemukan dalam daftar array maka tampilkan pesan
            if(!in_array($request_url, $bypass)) {
                if (!CSRF::validate($getToken) ) {
                    throw new Exception("Invalid CSRF token! Please add csrf token to your code");
                    die();
                }
            }
        }
    }

    /**
     * To get full url with current request
     */

    private function getCurrentRequest()
    {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $current_request = str_replace(PROJECTURL, '', $actual_link);
        $current_request = strtok($current_request, '?');
        return $current_request;
    }

    /**
     * To matching route
     */
    private function matchRoute($url) {
        $route = new Route;
        $routeArr = $route->getRouteArray();

        // auto route
        $this->autoRouteMatching();
        
        // manual route
        $url = trim($url, '/');

        if(empty($url)) {
            $url = '/';
        }

        for ($i=0; $i < count($routeArr) ; $i++) { 

            $routeArr[$i][0] = trim($routeArr[$i][0], '/');

            if (empty($routeArr[$i][0])) {
                $routeArr[$i][0] = '/';
            }

            if ($routeArr[$i][0] === $url) {

                // option route
                if (isset($routeArr[$i][5])) {
                    
                    if (isset($routeArr[$i][5]['middleware'])) {
                        $middleware_class = new $routeArr[$i][5]['middleware'][0]();
                        $method_name  = (String) $routeArr[$i][5]['middleware'][1];
                        return $middleware_class -> $method_name();
                    }

                }

                $route_method_type = (String) $routeArr[$i][3];

                if ($route_method_type !== $_SERVER['REQUEST_METHOD'] && $route_method_type !== 'Any') {
                    throw new Exception("Please visit this route with " . $route_method_type . ' http method');
                }
                
                if ($routeArr[$i][1] instanceof Closure) {
                    return call_user_func($routeArr[$i][1]);
                }

                if (is_array($routeArr[$i][1])) {
                    $class_name = $routeArr[$i][1][0];
                    $method_name = $routeArr[$i][1][1];

                    if (class_exists($class_name)) {
                        $class_name = new $class_name();
                        return $class_name -> $method_name();
                    } else {
                        throw new Exception("Class " . $class_name . ' not available');
                    }
                }

            } 
        }

        return $this->error404();
    }

    /**
     * Auto route
     */
     private function autoRouteMatching()
     {
        $route = new Route;
        $explode_request = explode('/', trim($this->getCurrentRequest(), '/'));
        $file_path = null;
        $auto_method_name = $explode_request[count($explode_request) - 1];
        
        for ($i=0; $i < count($explode_request) - 1; $i++) { 
            $file_path .= $explode_request[$i] . '\\';
        }

        $file_path = rtrim($file_path, '\\');

        $auto_className = '\\App\\Controllers\\' . $file_path . 'Controller';
        
        if (class_exists($auto_className) && $route->getAutoRoute()) {
            $newController  = new $auto_className;
            $auto_methodName = (String) $auto_method_name;
            return $newController->$auto_methodName();
        }

        return false;
     }
}
