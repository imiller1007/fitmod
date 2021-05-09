<?php

    class Pages extends Controller{

        public function __construct(){

        }

        public function index(){
            redirect('mods');
        }


        public function about(){
            $data = [
                'title' => 'About Us'
            ];
            $this->view('pages/about', $data);
        }

        public function test(){
            $data = [];

            $this->view('pages/test', $data);
        }
    }