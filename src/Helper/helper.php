<?php

namespace ofi\ofi_php_framework\Helper;

use App\users;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use ofi\ofi_php_framework\Controller;
use Exception;

class helper extends Controller
{
    /**
     * Method hash
     * For hashing a sensitive word
     * like a password.
     */
    public static function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Method random
     * to generate random string
     */

    public static function random($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Method slug
     * this method is to replace space
     * in a words to '-' sign.
     */
    public function slug($string)
    {
        return strtolower(str_replace(' ', '-', $string));
    }

    /**
     * Method  blockIp
     * This function is detect a IP, what thats IP is
     * allow to visit your sites or not. When not allowed
     * your visitor will have a 500 error form they browser.
     */
    public static function blockIp()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        // search IP in blockIp.php
        include 'App/Middleware/blockIp.php';

        $z = array_search($ip, $block);

        if (in_array($ip, $block)) {
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header($_SERVER['SERVER_PROTOCOL'].' 503 Service Temporarily Unavailable', true, 503);
            exit();
        }
    }

    /**
     * This function can remove a folder
     * and all files inside the folder
     */
    
    public static function deleteFolder($dirname = null) {
        
        if(!isset($dirname) || $dirname == '') {
            throw new Exception("Path can't null!", 500);
        }
        
       $path = UPLOADPATH . '/' . $dirname;
        
        if(!is_dir($path)) {
            $status['status'] = 'Folder '. $dirname .' not found!';
            $status['path'] = $path;
            
            return $status;
            die();
        }
        
        array_map('unlink', glob("$path/*.*"));
        rmdir($path);
        
        $status['status'] = 'success';
        $status['path'] = UPLOADPATH . '/' . $dirname;
        
        return $status;
    }
    
    /**
     * Function to delete a file
     **/
    
    public static function deleteFile($fileName = null) {
        $status = [];
        
        if(!isset($fileName) || $fileName == '') {
            throw new Exception("Filename can't null!", 500);
        }
        
        $file = UPLOADPATH .'/'. $fileName;
        
        if(!is_file($file)) {
            $status['status'] = 'File not found!';
            $status['filename'] = $fileName;
            
            return $status;
            die();
        } 
        
        // Jika file ada maka hapus
        if(unlink($file)) {
            $status['status'] = 'success';
            $status['filename'] = $fileName;
        } else {
            $status['status'] = 'error';
            $status['filename'] = $fileName;
        }
        
        return $status;
    }

    /**
     * Method upload
     * Help to upload a file
     */

    public static function upload($data)
    {
        include 'mimes.php';
        // $data['form'] adalah nama form input yang menjadi acuan
        // $data['folder'] adalah nama folder tujuan untuk menjadi penyimpanan
        
        // Cek apakah data form dan folder sudah diset atau belum
        if(!isset($data['folder']) && !isset($data['form'])) {
            throw new Exception("All request can't null", 1);
        }

        $ekstensi_diperbolehkan	= $mimes;
        $nama = strtolower(self::random(rand(4, 22))) . '-' . str_replace(' ', '-', $_FILES[$data['form']]['name']);
        $x = explode('.', $nama);
        $ekstensi = strtolower(end($x));
        $ukuran	= $_FILES[$data['form']]['size'];
        $file_tmp = $_FILES[$data['form']]['tmp_name'];	
        
        
        // Cek apakah ketika menerima file terdapat error atau tidak
        if(isset($_FILES[$data['form']]['error']) && $_FILES[$data['form']]['error'] == 1) {
            $status['status'] = 'ERROR WHILE RECEIVE FILES! Please try other file';
            $status['filename'] = null;
            $status['filesize'] = null;
            $status['storageLocation'] = null;
            return $status;
            die();
        }
        
            if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
                if($ukuran <= MAXUPLOAD){			
                    
                    // Cek apakah folder upload sudah tersedia?
                    if(!is_dir(UPLOADPATH)) {
                        throw new Exception("Root folder to store this file are not found!", 404);
                    }
                    
                    // Otomatis membuat direktori baru jika direktori yang diminta tidak ditemukan
                    $dir = UPLOADPATH . '/' . $data['folder'];
                    
                    if (!file_exists( $dir ) && !is_dir($dir)) {
                        mkdir(UPLOADPATH . '/' . $data['folder']);
                    } 

                    move_uploaded_file($file_tmp, $dir . '/' . $nama);

                    $bytes = $ukuran;

                    if ($bytes >= 1073741824)
                    {
                        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
                    }
                    elseif ($bytes >= 1048576)
                    {
                        $bytes = number_format($bytes / 1048576, 2) . ' MB';
                    }
                    elseif ($bytes >= 1024)
                    {
                        $bytes = number_format($bytes / 1024, 2) . ' KB';
                    }
                    elseif ($bytes > 1)
                    {
                        $bytes = $bytes . ' bytes';
                    }
                    elseif ($bytes == 1)
                    {
                        $bytes = $bytes . ' byte';
                    }
                    else
                    {
                        $bytes = '0 bytes';
                    }
                    
                    $status['status'] = 'Success';
                    $status['filename'] = $nama;
                    $status['filesize'] = $bytes;
                    $status['storageLocation'] = PROJECTURL . '/assets/upload/' . $data['folder'] . '/' . $nama;
                    return $status;

                }else{
                    $status['status'] = 'THE SIZE OF FILE IS TOO LARGE';
                    $status['filename'] = null;
                    $status['filesize'] = null;
                    $status['storageLocation'] = null;
                    return $status;
                }    
            }else{
                    $status['status'] = 'EXTENSION OF FILES IS NOT ALLOWED';
                    $status['filename'] = null;
                    $status['filesize'] = null;
                    $status['storageLocation'] = null;
                    return $status;
            }
    }

    /**
     * Method auth
     * is to get your auth information
     * from the database (login required to use).
     */
    public static function auth($data)
    {
        $app_id_user = parent::getSession('app_id_user');
        
        if($app_id_user) {
            $users = users::where('id', $app_id_user) -> first();

            if($users) {
                if(isset($data)) {
                    return $users -> $data;
                } else {
                    return $users;
                }
            } else {
                return 0;
            }
        } else {
            $controller = new Controller();
            $controller -> message()->flash()->error('You must login first', '/login');
        }
    }

    public static function sendEmail($data)
    {
        //SMTP needs accurate times, and the PHP time zone MUST be set
        //This should be done in your php.ini, but this is how to do it if you don't have access to that
        date_default_timezone_set('Asia/Jakarta');

        //Create a new PHPMailer instance
        $mail = new PHPMailer();

        //Tell PHPMailer to use SMTP
        $mail->isSMTP();

        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = SMTPDebug;

        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';

        //Set the hostname of the mail server
        $mail->Host = Host;

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = Port;

        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = SMTPSecure;

        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = GmailUsername;

        //Password to use for SMTP authentication
        $mail->Password = GmailPassword;

        //Set who the message is to be sent from
        $mail->setFrom(senderEmail, senderName);

        //Set who the message is to be sent to
        $mail->addAddress($data['to'], $data['receiverName']);

        //Set the subject line
        $mail->isHTML(true);
        $mail->Subject = $data['subject'];
        $mail->AltBody = $data['body'];
        $mail->Body = $data['body'];

        if ($data['attachment'] && $data['attachment']['type'] == 'url') {
            $mail->addStringAttachment(file_get_contents($data['attachment']['value']), $data['attachment']['name']);
        } else {
            $mail->addAttachment($data['attachment']['value']);
        }
        
        $mail->send();

        return $mail;
    }
}
