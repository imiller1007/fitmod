<?php

class Mods extends Controller
{

    public function __construct()
    {
        // if (!isLoggedIn()) {
        //     redirect('users/login');
        // }

        $this->modModel = $this->model('Mod');
        $this->userModel = $this->model('User');
    }

    public function index(){
        // Get mods
        $mods = $this->modModel->getMods();

        $data = [
            'mods' => $mods
        ];

        $this->view('mods/index', $data);
    }



}