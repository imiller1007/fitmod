<?php

class Users extends Controller
{

    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->workoutModel = $this->model('Workout');
    }

    public function index()
    {
        if(isset($_SESSION['user_id'])){
            redirect('mods/');
        }else{
            redirect('users/login');
        }
    }

    public function register()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'user_email' => trim($_POST['user_email']),
                'user_first' => trim($_POST['user_first']),
                'user_last' => trim($_POST['user_last']),
                'password' => trim($_POST['password']),
                'confirm_pass' => trim($_POST['confirm_pass']),
                'user_email_err' => '',
                'user_first_err' => '',
                'user_last_err' => '',
                'password_err' => '',
                'confirm_pass_err' => ''
            ];

            // Validate Email
            if (empty($data['user_email'])) {
                $data['user_email_err'] = 'Please enter email';
            } else {
                // Check email
                if ($this->userModel->findUserByEmail($data['user_email'])) {
                    $data['user_email_err'] = 'Email is tied to an existing account';
                }
            }

            // Validate First Name
            if (empty($data['user_first'])) {
                $data['user_first_err'] = 'Please enter your first name';
            }

            // Validate Last Name
            if (empty($data['user_last'])) {
                $data['user_last_err'] = 'Please enter your last name';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 8) {
                $data['password_err'] = 'Password must be at least 8 characters';
            }

            // Validate Confirm Password
            if(empty($data['confirm_pass'])){
                $data['confirm_pass_err'] = 'Please confirm password';
            } else {
                if($data['password'] != $data['confirm_pass']){
                    $data['confirm_pass_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if(empty($data['user_email_err']) && empty($data['user_first_err']) && empty($data['user_last_err']) && empty($data['password_err']) && empty($data['confirm_pass_err'])) {
                // Validated
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
                
                // Register User
                if($this->userModel->register($data)){
                    flash('register_success', 'Account created!');
                    redirect('users/login');
                }


            }else{
                // Load view with errors
                $this->view('users/register', $data);
            }


        } else {
            if (isset($_SESSION['user_id'])){
                redirect('mods');
            }
            // Init data
            $data = [
                'user_email' => '',
                'user_first' => '',
                'user_last' => '',
                'password' => '',
                'confirm_pass' => '',
                'user_email_err' => '',
                'user_first_err' => '',
                'user_last_err' => '',
                'password_err' => '',
                'confirm_pass_err' => ''
            ];

            // Load view
            $this->view('users/register', $data);
        }
    }

    public function login(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'user_email' => trim($_POST['user_email']),
                'password' => trim($_POST['password']),
                'user_email_err' => '',
                'password_err' => ''
            ];

            // Validate Email
            if(empty($data['user_email'])){
                $data['user_email_err'] = 'Please enter email';
            }

            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if($this->userModel->findUserByEmail($data['user_email'])){
                // User found
            } else {
                // User not found
                $data['user_email_err'] = 'No user found';
            }

            // Make sure errors are empty
            if(empty($data['user_email_err']) && empty($data['password_err'])){
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['user_email'], $data['password']);

                if($loggedInUser){
                    // Create Session
                    $this->createUserSession($loggedInUser);
                    setWorkoutStatusSession($this->workoutModel->getCurrentWorkout($_SESSION['user_id']));
                }else{
                    $data['password_err'] = 'Password incorrect';

                    $this->view('users/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }
        }else{
            if (isset($_SESSION['user_id'])){
                redirect('mods');
            }
            // Init data
            $data = [
                'user_email' => '',
                'password' => '',
                'user_email_err' => '',
                'password_err' => ''
            ];

            // Load view
            $this->view('users/login', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['user_first'] = $user->user_first;
        $_SESSION['user_last'] = $user->user_last;
        $_SESSION['user_email'] = $user->user_email;
        //redirect
        redirect('mods');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_first']);
        unset($_SESSION['user_last']);
        unset($_SESSION['user_email']);
        session_destroy();
        redirect('users/login');
    }
}
