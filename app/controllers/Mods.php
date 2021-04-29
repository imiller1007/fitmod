<?php

class Mods extends Controller
{

    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $this->modModel = $this->model('Mod');
        $this->userModel = $this->model('User');
        $this->exerciseModel = $this->model('Exercise');
    }

    public function index(){
        // Get mods
        $mods = $this->modModel->getMods();

        $data = [
            'mods' => $mods
        ];

        $this->view('mods/index', $data);
    }

    public function add(){

        $exercises = $this->exerciseModel->getExercises();

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Sanitize POST array
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Set form values to DATA variables
            $inputData = array();
            $errors = array();

            $inputData['title'] = trim($_POST['title']);
            $inputData['desc'] = trim($_POST['desc']);
            $errors['titleErr'] = '';
            $errors['descErr'] = '';
            $errors['numOfExerErr'] = '';
            for($i = 1; $i <= 10; $i++){
                $inputData['exerciseName' . $i] = trim($_POST['exerciseName' . $i]);
                $inputData['exerciseType' . $i] = trim($_POST['exerciseType' . $i]);
                $inputData['exerciseTarget' . $i] = trim($_POST['exerciseTarget' . $i]);
                $inputData['exerciseNum' . $i] = trim($_POST['exerciseNum' . $i]);
                $errors['exerNameErr' . $i] ='';
                $errors['exerTypeErr' . $i] ='';
                $errors['exerTargetErr' . $i] ='';
                $errors['exerNumErr' . $i] ='';
            };

            $data = [
                'numOfExer' => trim($_POST['exerciseNum']),
                'exercises' => $exercises,
                'inputData' => $inputData,
                'errors' => $errors
            ];

            $this->view('mods/add', $data);

        }else{

            // generate input fields to store form data after POST . . . 
            // . . . ALSO generate error fields for all input fields
            $inputData = array();
            $errors = array();

            $inputData['title'] = '';
            $inputData['desc'] = '';
            $errors['titleErr'] = '';
            $errors['descErr'] = '';
            $errors['numOfExerErr'] = '';
            for($i = 1; $i <= 10; $i++){
                $inputData['exerciseName' . $i] = '';
                $inputData['exerciseType' . $i] = '';
                $inputData['exerciseTarget' . $i] = '';
                $inputData['exerciseNum' . $i] = '';
                $errors['exerNameErr' . $i] = '';
                $errors['exerTypeErr' . $i] = '';
                $errors['exerTargetErr' . $i] = '';
                $errors['exerNumErr' . $i] = '';
            };

            $data = [
                'numOfExer' => '',
                'exercises' => $exercises,
                'inputData' => $inputData,
                'errors' => $errors
            ];

            $this->view('mods/add', $data);
        }
    }



}