<?php

class Exercises extends Controller
{
    public function __construct()
    {
        $this->userModel = $this->model('User');
        $this->exerciseModel = $this->model('Exercise');

        if (isLoggedIn()) {
            if($_SESSION['admin'] != 1){
                redirect('mods');
            }
        }else{
            redirect('mods');
        }
    }

    public function index(){
        // get page number or default it to 1
        if (isset($_GET['pageNum'])) {
            $pageNum = $_GET['pageNum'];
        } else {
            $pageNum = 1;
        }

        if (!is_numeric($pageNum)) {
            $pageNum = 1;
        }

        // max records per page
        $recordsPerPage = 100;
        $offset = ($pageNum - 1) * $recordsPerPage;

        // get row count, calculate total pages
        $totalRows = $this->exerciseModel->getExerciseCount();
        $totalPages = ceil($totalRows / $recordsPerPage);

        // Get exercises for page
        $exercises = $this->exerciseModel->getExercisesPaginated($offset, $recordsPerPage);

        $data = [
            'totalPages' => $totalPages,
            'exercises' => $exercises,
            'pageNum' => $pageNum
        ];

        $this->view('exercises/index', $data);
    }

    public function editAjax(){
        // check if logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        } else {
            $userId = $_SESSION['user_id'];
        }

        // check if this method was called from a post call
        if (!isset($_POST['exercise_id'])) {
            redirect('exercises');
        }

        // set exercise ID from posted ID
        $exerId = $_POST['exercise_id'];

        // check if ID exists in DB
        if (!$this->exerciseModel->getExerciseById($exerId)) {
            die(error('Error: exercise does not exist'));
        }

        $updateData = [
            'exercise_id' => $_POST['exercise_id'],
            'exercise_name' => $_POST['exercise_name'],
            'body_group' => $_POST['exercise_target'],
            'exercise_type' => $_POST['exercise_type']
        ];

        if ($this->exerciseModel->update($updateData)) {
            $message = 'Mod saved!';

            $response = array();
            $response['success'] = true;
            $response['message'] = $message;

            echo json_encode($response);
        } else {
            die(error('Error: Something went wrong'));
        }
    }

    public function deleteAjax(){
        // check if logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        } else {
            $userId = $_SESSION['user_id'];
        }

        // check if this method was called from a post call
        if (!isset($_POST['exercise_id'])) {
            redirect('exercises');
        }

        // set exercise ID from posted ID
        $exerId = $_POST['exercise_id'];

        // check if ID exists in DB
        if (!$this->exerciseModel->getExerciseById($exerId)) {
            die(error('Error: exercise does not exist'));
        }

        $deleteData = [
            'exercise_id' => $exerId
        ];

        if ($this->exerciseModel->delete($deleteData)) {
            $message = 'Mod saved!';

            $response = array();
            $response['success'] = true;
            $response['message'] = $message;

            echo json_encode($response);
        } else {
            die(error('Error: Something went wrong'));
        }

    }

}