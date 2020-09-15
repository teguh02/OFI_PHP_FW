<?php

namespace ofi\ofi_php_framework\Support\Curl;

use Exception;

class GET {

    protected $methods = 'GET';

    public function GET(String $URL, Array $headers)
    {
      array_push($header, 'content-type: application/x-www-form-urlencoded');
      array_push($header, 'Origin: ' . PROJECTURL);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $URL,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $this->methods,
          // CURLOPT_VERBOSE => true,
        //   CURLOPT_HTTPHEADER => array(
        //     "key: your-api-key"
        //   ),

            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          throw new Exception($err, 1);
        } else {
          return $response;
        }
    }
}