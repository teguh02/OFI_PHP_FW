<?php 

namespace ofi\ofi_php_framework\Support\Curl;

use Exception;

class POST {

    protected $methods = 'POST';

    public function POST($url, $body, Array $header)
    {
        array_push($header, 'content-type: application/x-www-form-urlencoded');
        array_push($header, 'Origin: ' . PROJECTURL);

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => $this->methods,
          // CURLOPT_VERBOSE => true,
        //   CURLOPT_POSTFIELDS => "origin=501&originType=city&destination=574&destinationType=subdistrict&weight=1700&courier=jne",
         CURLOPT_POSTFIELDS => $body,
        //   CURLOPT_HTTPHEADER => array(
        //     "content-type: application/x-www-form-urlencoded",
        //     "key: your-api-key"
        //   ),

            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          throw new Exception($err, 1);
          die();
        } else {

          if(isset($response) && $response != null) {
              return $response;
          }  else {
              return '1';
          }
        }
    }
}