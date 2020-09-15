<?php 

namespace ofi\ofi_php_framework\Controller;

use vendor\OFI_PHP_Framework\Controller;
use App\provider\event;
use App\users;

trait auth {
    // Redirect to login page
    public function login()
    {
        $controller = new Controller();

        if ($controller->getSession('app_id_user')) {
            $controller->response() -> redirect('home');
        } else {
            $controller->loadTemplate('Auth\login', null);
        }
    }

    // Redirect to register page
    public function register()
    {
        $controller = new Controller();

        if ($controller->getSession('app_id_user')) {
            $controller->response() -> redirect('home');
        } else {
            $controller->loadTemplate('Auth\register', null);
        }
    }

    public function deteksiLogin()
    {
        $event = new event();
        $event->whenLogin();

        if($this->must_post()) {
            $this->whenLogin();
            
            $password = $this->request()->input('password');
            $emailorusername = $this->request()->input('emailorusername');

            $users = users::where('email', $emailorusername) -> orWhere('username', $emailorusername) -> first();

            if($users) {
                if(password_verify($password, $users->password)) {
                    $this->setSession('app_id_user', $users->id);
                    $this->message()->js()->success('Successfuly to login', '/home');
                } else {
                    $this->message()->js()->error('Username or password is wrong', '/login');
                }
            } else {
                $this->message()->js()->error('Users not found!', '/login');
            }
        }
    }

    public function logout()
    {
        $controller = new Controller();
        $event = new event();

        if($controller->must_post()) {
            $event->whenLogout();
            
            $controller->setSession('app_id_user', null);
            $controller->message()->js()->success('Successfuly logout', '/login');
        }
    }

    public function saveRegisterData($data)
    {
        // Cek user berdasarkan email dan username
        // apakah sudah ada atau belum

        $event = new event();
        $event->whenRegistration();

        $cek = users::where('email', $data['email']) 
                -> orWhere('username', $data['username']) 
                -> first();

        if($cek) {
            $this->message()->flash()->error('Username or email are registered in our system', '/register');
            die();
        }
        
        // Jika belum ada maka save data
        if (users::insert($data)) {
            $this->message()->js()->success('Successfuly save your account', '/register');
        } else {
            $this->message()->js()->error('Failed to save your account', '/register');
        }
    }
}