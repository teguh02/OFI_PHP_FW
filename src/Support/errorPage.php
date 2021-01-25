<?php 

namespace ofi\ofi_php_framework\Support;

/**
 * Trait error page
 */

trait errorPage {

    /**
     * To show 404 error
     */

    public function error404()
    {
        $this->whenError();
        $viewData['status'] = 404;
        $viewData['title'] = 'Not Found';
        $viewData['msg'] = 'Sorry but the page you are looking for does not exist, have been removed.';

        $this->pageError($viewData);
    }

    /**
     * To show 500 error
     */

    public function error500($pesan)
    {
        $this->whenError();
        $viewData['status'] = 500;
        $viewData['title'] = 'Server Error';
        $viewData['msg'] = $pesan;

        $this->pageError($viewData);
    }

    /**
     * Page error template
     */

    private function pageError($viewData)
    {
        extract($viewData);

        $file = BASEURL . '/resources/views/errorPage/error.ofi.php';

        if (file_exists($file)) {
            if (defined('overwriteErrorPage') && overwriteErrorPage !== false) {
                include_once $file;
                die();
            }
        }

        echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title> '. $status .' Error - '. PROJECTNAME .'</title>
                <link rel="stylesheet" type="text/css" href="'. PROJECTURL .'/assets/css/bootstrap.min.css">
                <style>body {margin: 0px !important;}html, body {overflow: hidden !important;}</style>
            </head>

            <body>
                <div class="container-fluid text-center d-flex justify-content-center align-items-center" style="height: 100vh;">

                    <div>
                        <h1 class="display-2"> '. $status .' ' . $title . '</h1>
                        <p>
                            '. $msg .'
                        </p>

                        <br>

                        <a onclick="window.history.back();" href="#">
                            <button class="btn btn-light border w-50">Go Back</button>
                        </a>
                    </div>

                </div>
            </body>
            </html>
        ';
    }
}