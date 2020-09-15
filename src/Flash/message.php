<?php

namespace ofi\ofi_php_framework\Flash;

use \Plasticbrain\FlashMessages\FlashMessages;

trait message {

    /**
     * Menyediakan dua opsi flash message
     * yang pertama flash message PHP
     * yang kedua flash message javascript
     */

    protected $message_status = false;
    protected $js_message = false;

    public function message()
    {
        $this->message_status = true;
        return $this;
    }

    public function flash()
    {
        if ($this->message_status) {
            return new FlashMessages();
        }

        return false;
    }

    public function js()
    {
        $this->js_message = true;
        return $this;
    }

    /**
     * Mencetak pesan sukses menggunakan javascript
     */

    public function success($params, $redirect)
    {
        if ($this->js_message) 
        echo "
            <body>
                <script src='/assets/js/alertjs/alert.js'></script>

                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Successfuly',
                        text: '". $params ."',
                    })

                    setInterval(function() {
                        window.location.href = '" . PROJECTURL . $redirect . "'
                    }, 1000)
                </script>
            </body>
        ";
    }

    /**
     * Mencetak pesan warning menggunakan javascript
     */

    public function warning($params, $redirect)
    {
        if ($this->js_message) 
        echo "
            <body>
                <script src='/assets/js/alertjs/alert.js'></script>

                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Warning!',
                        text: '". $params ."',
                    })

                    setInterval(function() {
                        window.location.href = '" . PROJECTURL . $redirect . "'
                    }, 1000)
                </script>
            </body>
        ";
    }

    /**
     * Mencetak pesan info menggunakan javascript
     */

    public function info($params, $redirect)
    {
        if ($this->js_message) 
        echo "
            <body>
                <script src='/assets/js/alertjs/alert.js'></script>

                <script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Information!',
                        text: '". $params ."',
                    })

                    setInterval(function() {
                        window.location.href = '" . PROJECTURL . $redirect . "'
                    }, 1000)
                </script>
            </body>
        ";
    }

    /**
     * Mencetak pesan alert menggunakan javascript
     */

    public function error($params, $redirect)
    {
        if ($this->js_message) 
        echo "
            <body>
                <script src='/assets/js/alertjs/alert.js'></script>

                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'error!',
                        text: '". $params ."',
                    })

                    setInterval(function() {
                        window.location.href = '" . PROJECTURL . $redirect . "'
                    }, 1000)
                </script>
            </body>
        ";
    }
}