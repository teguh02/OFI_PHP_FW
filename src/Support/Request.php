<?php 

namespace ofi\ofi_php_framework\Support;

use Exception;

/**
 * Trait Request
 */

trait Request {

    /**
     * Chaining method request
     */

    protected $data = [];
    private $request = null;
    private $header_request = false;
    protected $whenError = "Wrong method please select request chain method first";

    /**
     * To start use request chain method
     */

    public function request()
    {   
        $this->request = true;
        return $this;
    }

    public function all()
    {
        if($this->request) {
            // Jika header_request true maka tampilkan semua data heder
            if($this->header_request) {
                return getallheaders();
            } else {

                // Namun jika header_request false maka tampilkan 
                // semua data raw yang masuk

                if($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $json = file_get_contents('php://input');
                    return json_decode($json, true);
                } else {
                    throw new Exception("Wrong HTTP Method, you must use POST method", 1);
                }
            }
        } else {
            throw new Exception($this->whenError, 1);
        }
    }

    public function input($val_request)
    {
        if($this->request) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return $_REQUEST[$val_request];     
            } else {
                return $_GET[$val_request];
            }
        } else {
            throw new Exception($this->whenError, 1);
        }
    }

    /**
     * To start use all headers request
     */

    public function header()
    {
        if ($this->request) {
            $this->header_request = true;
            return $this;
        } else {
            throw new Exception($this->whenError, 1);
        }
    }

    /**
     * To get only one value from all headers data
     */

    public function first($request_val)
    {
        if($this->request) {
            if($this->header_request) {
                $header = getallheaders();
                return $header[$request_val];
            } else {
                throw new Exception("Wrong method please select header method first", 1);
            }
        } else {
            throw new Exception($this->whenError, 1);
        }
    }

    /**
     * To start to get all cookie data
     */

    public function Cookie($value = null)
    {
        if ($this->request) {
            if(isset($value)) {
                return $_COOKIE[$value];
            } else {
                return $_COOKIE;
            }
        } else {
            throw new Exception($this->whenError, 1);
        }
    }

    /**
     * To set cookie in your system
     * $key = is key what do you want to set
     * $value = is a value from the key
     * $exprie = is a expire from your cookie (in day)
     */

    public function setCookie($key, $value, $exprie = 1) 
    {
        if(isset($key) && isset($value) && isset($exprie))  {
            setcookie($key, $value, time() + $exprie, '/');   
            
           $cookie_name = $key;

           if(!isset($_COOKIE[$cookie_name])) {
                return 1;
           } else {
                return 0;
           }

        } else {
            throw new Exception("All variable doesn't null", 1);
        }
    }

    /**
     * To Remove cookie value according cookie key
     */

    public function removeCookie($key = null)
    {
        if (isset($key)) {
           setcookie($key, time() + 3600, '/');   

           $cookie_name = $key;

           if(!isset($_COOKIE[$cookie_name])) {
                return 1;
           } else {
                return 0;
           }

        } else {
            throw new Exception("Key doesn't null", 1);
        }
    }
}