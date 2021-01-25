<?php

namespace ofi\ofi_php_framework\Cli;

include_once dirname(__DIR__) . '/../vendor/autoload.php'; 

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class OfiCli extends Cli {
    
    // register options and arguments
    protected function setup(Options $options)
    {
        $options->setHelp('OFI PHP Framework Cli Tools');

        $options->registerCommand('serve', 'Run Development Mode');
        $options->registerCommand('makeController', 'Make controller file');
        $options->registerCommand('makeModel', 'Make model file');
    }

    // implement your code
    protected function main(Options $options)
    {
        switch ($options -> getCmd()) {

            case 'serve':
                chdir(BASEURL);
                shell_exec("php -S localhost:9000");
                break;

            case 'makeModel':
                $destination = BASEURL . 'App' . DIRECTORY_SEPARATOR;
                $sourceFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Files' . DIRECTORY_SEPARATOR . 'sampleModel.php';

                if (!is_dir($destination)) {
                    $this->error('Controller folder not found!');
                }

                if (!file_exists($sourceFile)) {
                    $this->error('sampleModel not found');
                }

                $filename = '';
                if (!file_exists($destination . DIRECTORY_SEPARATOR . 'sampleModel.php')) {
                    $filename = 'sampleModel.php';
                    copy($sourceFile, $destination . $filename);
                } else {
                    $filename = 'sampleModel.'. time() .'.php';
                    copy($sourceFile, $destination . $filename);
                }

                $this->success("Model " . $filename . ' successfuly created!');

                break;

            case 'makeController':
                $destination = BASEURL . 'App' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR;
                $sourceFile = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Files' . DIRECTORY_SEPARATOR . 'sampleController.php';

                if (!is_dir($destination)) {
                    $this->error('Controller folder not found!');
                }

                if (!file_exists($sourceFile)) {
                    $this->error('sampleController not found');
                }

                $filename = '';
                if (!file_exists($destination . DIRECTORY_SEPARATOR . 'sampleController.php')) {
                    $filename = 'sampleController.php';
                    copy($sourceFile, $destination . $filename);
                } else {
                    $filename = 'sampleController.'. time() .'.php';
                    copy($sourceFile, $destination . $filename);
                }

                $this->success("Controller " . $filename . ' successfuly created!');
                break;
            
            default:
                echo $options->help();
                break;
        }
    }
}