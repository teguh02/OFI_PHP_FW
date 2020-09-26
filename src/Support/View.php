<?php 

namespace ofi\ofi_php_framework\Support;

/**
 * Trait to load view
 */

use DebugBar\StandardDebugBar;
use Exception;
use ofi\ofi_php_framework\Helper\helper;
use ofi\ofi_php_framework\Controller\route as Route;

trait View {

    /**
     * To load view file
     */
    public function loadView($viewName, $viewData = [])
    {
        $this->loadTemplate($viewName, $viewData);
    }

    /**
     * To load standart html 5 template
     */

    public function loadTemplate($viewName, $viewData = [])
    {
        if (!empty($viewData)) {
            extract($viewData);
        }

        if(ENVIRONMENT == 'development') {
            $debugbar = new StandardDebugBar();
            $debugbarRenderer = $debugbar->getJavascriptRenderer();	
            $debugbar["messages"]->addMessage("OFI PHP Framework Ready To Use!");
            $debugbar["messages"]->addMessage("Project URL : " . BASEURL);
            $debugbar["messages"]->addMessage("Current URL Name : " . Route::getName());
        }

        echo '
            <!DOCTYPE html>
            <html>
            <head>
                <title>' . PROJECTNAME . '</title>
                <meta charset="utf-8">
                <meta name="description" content="'. DESCRIPTION .'" >
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta property="og:title" content="'. PROJECTNAME .'" />
                <meta property="og:type" content="website" />
                <meta property="og:url" content="'. PROJECTURL .'" />
                <link rel="shortcut icon" href="'. PROJECTURL .'/assets/favicon.png">
                <meta property="og:image" content="'. PROJECTURL .'/assets/favicon.png" />
                <meta name="robots" content="index, follow">
                <meta name="keywords" content="'. KEYWORDS .'">
                <meta name="author" content="'. AUTHOR .'">
                <meta name="google-site-verification" content="'. GoogleSiteVerification .'" />

                <!-- Use this csrf token to access POST methods when you use javascript -->
                ' . CSRF . '

                    <link rel="stylesheet" type="text/css" href="'. PROJECTURL .'/assets/css/bootstrap.min.css">
                    <script src="'. PROJECTURL .'/assets/js/jquery.min.js"></script>
                    <script src="'. PROJECTURL .'/assets/js/bootstrap.min.js"></script>';
                    if(ENVIRONMENT == "development") echo $debugbarRenderer->renderHead();
            echo '
            </head>
            <body>';

                $this->loadViewInTemplate($viewName,$viewData);
                if(ENVIRONMENT == "development") echo $debugbarRenderer->render();

            echo '
            </body>
            </html>
            ';
    }

    public function loadViewInTemplate($viewName, $viewData)
    {
        /**
         * Pertama kita perlu mengecek apakah file ada 
         * atau tidak
         */

        $path_to_file = BASEURL . '/resources/views/' . str_replace('\\', '/', $viewName) . '.ofi.php';

        // Jika file ada
        if(is_file($path_to_file)) {
            // Tampilkan template
            $flash = new \Plasticbrain\FlashMessages\FlashMessages();
            $helper = new helper();
            $Route = new Route();
            if(!empty($viewData)) {
                extract($viewData);
            }
            include $path_to_file;
        } else {
            // Jika tidak maka berikan pesan error
            throw new Exception("File " . $viewName . '.ofi.php at ' . $path_to_file . ' not found!' , 404);
        }
    }
}