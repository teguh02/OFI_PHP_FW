<?php

namespace ofi\ofi_php_framework;

use App\provider\event;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use ofi\ofi_php_framework\Flash\message;
use ofi\ofi_php_framework\Support\errorPage;
use ofi\ofi_php_framework\Support\Request;
use ofi\ofi_php_framework\Support\Response;
use ofi\ofi_php_framework\Support\Session;
use ofi\ofi_php_framework\Support\View;

class Controller extends event
{
    protected $data = [];
    private $response = null;
    private $request = null;
    private $header_request = false;

    use message;
    use Request;
    use Response;
    use View;
    use errorPage;
    use Session;

    public function __construct()
    {
        global $config;
        $capsule = new DB;
        $capsule->addConnection([
            'driver'    => $config['driver'] != null ? $config['driver'] : 'mysql' ,
            'host'      => $config['localhost'] != null ? $config['localhost'] : 'localhost',
            'port'      => $config['port'] != null ? $config['port'] : '3306',
            'database'  => $config['database'] != null ? $config['database'] : 'ofi',
            'username'  => $config['username'] != null ? $config['username'] : 'root',
            'password'  => $config['password'] != null ? $config['password'] : '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        // Set the event dispatcher used by Eloquent models... (optional)
        $capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();
    }

    /**
     * Untuk memvalidasi bahwa untuk mengunjungi suatu method 
     * yang diberi kode ini harus menggunakan http method post
     */

    public function must_post()
    {
        if ($_SERVER['REQUEST_METHOD'] != "POST") {
            $this -> error500("Error, because this method are using POST method");
        } else {
            return true;
        }
    }
}
