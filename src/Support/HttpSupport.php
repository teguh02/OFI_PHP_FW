<?php

namespace ofi\ofi_php_framework\Support;

use Exception;
use ofi\ofi_php_framework\Support\Curl\DELETE;
use ofi\ofi_php_framework\Support\Curl\GET;
use ofi\ofi_php_framework\Support\Curl\POST;
use ofi\ofi_php_framework\Support\Curl\PUTS;

class HttpSupport {

    /**
     * Define selected HTTP Methods is null
     */

    protected $selected_http_method = null;

    /**
     * Define Http methods Support is turn off
     */
    protected $http = false;

    /**
     * Default is GET HTTP Methods
     */

    protected $default_methods = 'GET';


    /**
     * Define null array for http headers value
     * Note : This value sended when this classes execute the CURL
     */

    protected $http_headers_array = [];


    /**
     * Define null data to CURL POSTFIELDS
     */

    protected $body_data = [];

    /**
     * Define null URL detsination
     */

    protected $url_destination = null;


    /**
     * Define CURL Debug is off
     */

    protected $CURLDEBUG = false;

    /**
     * Define HTTP Methods
     * Default is GET HTTP Methods
     */

    public function method(String $method)
    {
        $this->http = true;
        if(isset($method) && $method != $this->default_methods) {
            $this->selected_http_method = $method;
        } else {
            $this->selected_http_method = $this->default_methods;
        }
    
        return $this;
    }

    /**
     * Set array value for headers CURL
     */

    public function header(Array $array) 
    {
        /**
         * Penulisan array pada penggunan nantinya
         * array(
         *     "key: your-api-key"
         *   ),
         */

        if ($this->http) {
            $this->http_headers_array = $array;
            return $this;
        } else {
            throw new Exception("Please start HTTP class first! ", 1);
        }
    }

    public function url(String $url)
    {
        if (isset($url)) {
            $this->url_destination = $url;
            return $this;
        } else {
            throw new Exception("URL can't blank", 1);
        }
    }

    public function body(Array $data)
    {
        if (strtoupper($this->selected_http_method) == 'GET') { 
            throw new Exception("You can't use body methods with this HTTP Methods", 1);
            die();
        }

        $this->body_data = $data;
        return $this;
    }

    /**
     * To start execute this CURL
     */

    public function execute()
    {
        if ($this->http) {

        switch (strtoupper($this->selected_http_method)) {
            case 'GET':

                $get = new GET();
                $execute = $get->GET(
                                        $this->url_destination, 
                                        $this->http_headers_array
                                    );
                break;

            case 'POST':

                $body = http_build_query($this->body_data);

                $post = new POST();
                $execute = $post->POST(
                                        $this->url_destination, 
                                        $body,
                                        $this->http_headers_array
                );
                break;

            case 'PUT':
                $body = http_build_query($this->body_data);

                $put = new PUTS();
                $execute = $put->PUTS(
                                        $this->url_destination, 
                                        $body,
                                        $this->http_headers_array,
                                        "PUT"
                );
                break;

            case 'PATCH':
                    $body = http_build_query($this->body_data);
                
                    $put = new PUTS();
                    $execute = $put->PUTS(
                                            $this->url_destination, 
                                            $body,
                                            $this->http_headers_array,
                                            "PATCH"
                    );
                    break;

            case 'DELETE':
        
                $delete = new DELETE();
                $execute = $delete->DELETE(
                                    $this->url_destination, 
                                    $this->http_headers_array,
                                );
                break;
            
            default:
                throw new Exception($this->selected_http_method . ' HTTP Methods not found!', 1);
                break;
        }

        if($this->is_debug_on) {
            return $this;
        } else {
            return $execute;
        }
            
        } else {
            throw new Exception("Please start HTTP class first! ", 1);
        }
    }

}