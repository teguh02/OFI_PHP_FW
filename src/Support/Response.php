<?php 

namespace ofi\ofi_php_framework\Support;

/**
 * Trait Response
 */

use Exception;

trait Response {

    /**
     * Chaining method response
     */

    protected $data = [];
    private $response = null;

    public function response()
    {
        $this->response = true;
        return $this;
    }

    public function print_r($params)
    {

        if($this->response) {
            echo "<pre>";
                print_r($params);
            echo "<pre>";
        } else {
            throw new Exception("Wrong method please select response chain method", 1);
        }
    }

    public function var_dump($params)
    {
        if($this->response) {
            echo "<pre>";
                var_dump($params);
                die();
            echo "<pre>";
        } else {
            throw new Exception("Wrong method please select response chain method", 1);
        }
    }

    // Response print to json

    public function json($data, $code)
    {
        if($this->response) {
            // Set content type menjadi application/json
            header('Content-Type: application/json');
            http_response_code($code);
            echo json_encode($data);
        } else {
            throw new Exception("Wrong method please select response chain method", 1);
        }
    }

    /**
     * Method redirect
     * To give a new response redirect to a url.
     */

    public function redirect($url)
    {
        if($this->response) {
            if(strpos($url, 'http') !== false || strpos($url, 'https') !== false) {
                return header('Location: '.$url);
            } else {
                return header('Location: '. PROJECTURL . '/' . $url);
            }        
        } else {
            throw new Exception("Wrong method please select response chain method", 1);
        }
    }
}