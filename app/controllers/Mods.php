<?php

class Mods extends Controller
{

    public function __construct()
    {
        $this->modModel = $this->model('Mod');
        $this->savedModel = $this->model('SavedMod');
        $this->userModel = $this->model('User');
        $this->exerciseModel = $this->model('Exercise');
    }

    public function index()
    {
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
        $recordsPerPage = 5;
        $offset = ($pageNum - 1) * $recordsPerPage;

        // get row count, calculate total pages
        $totalRows = $this->modModel->getModCount();
        $totalPages = ceil($totalRows / $recordsPerPage);

        // Get mods for page
        $mods = $this->modModel->getPaginatedModsRecent($offset, $recordsPerPage);

        // Saved mods by user
        $savedModIds = array();
        if (isLoggedIn()) {
            $savedData = ['userId' => $_SESSION['user_id']];
            $savedMods = $this->savedModel->getModsSavedByUser($savedData);
            foreach ($savedMods as $savedMod) {
                array_push($savedModIds, $savedMod->mod_id);
            }
        }

        $data = [
            'pageNum' => $pageNum,
            'totalPages' => $totalPages,
            'mods' => $mods,
            'savedMods' => $savedModIds
        ];

        $this->view('mods/index', $data);
    }

    public function saveModAjax()
    {

        // check if logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        } else {
            $userId = $_SESSION['user_id'];
        }

        // check if this method was called from a post call
        if (!isset($_POST['modId'])) {
            redirect('mods');
        }

        // set mod ID from posted ID
        $modId = $_POST['modId'];

        // check if ID exists in DB
        if (!$this->modModel->findModById($modId)) {
            die(error('Error: Mod does not exist'));
        }

        // get ID of user who created mod
        $createdById = $this->modModel->findModById($modId)->created_by_id;

        // make sure mod can't be saved by author
        if ($createdById == $userId) {
            die(error('Error: Cannot save own mod'));
        }

        // check to make sure the mod isn't already saved by user
        if ($this->savedModel->getSavedModByModId($modId, $userId)) {
            die(error('Error: Already saved this mod'));
        }

        if ($this->savedModel->saveMod($modId, $userId)) {
            $message = 'Mod saved!';

            $response = array();
            $response['success'] = true;
            $response['message'] = $message;

            echo json_encode($response);
        } else {
            die(error('Error: Something went wrong'));
        }
    }

    public function unsaveModAjax()
    {
        // check if logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        } else {
            $userId = $_SESSION['user_id'];
        }

        // check if this method was called from a post call
        if (!isset($_POST['modId'])) {
            redirect('mods');
        }

        // set mod ID from posted ID
        $modId = $_POST['modId'];

        // check if ID exists in DB
        if (!$this->modModel->findModById($modId)) {
            die(error('Error: Mod does not exist'));
        }

        if($this->savedModel->unsaveMod($modId, $userId)){
            $message = 'Mod unsaved!';

            $response = array();
            $response['success'] = true;
            $response['message'] = $message;

            echo json_encode($response);
        }else{
            die(error('Error: Something went wrong'));
        }

    }

    public function add()
    {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $exercises = $this->exerciseModel->getExercises();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
            for ($i = 1; $i <= EXERCISEMAX; $i++) {
                $inputData['exerciseName' . $i] = trim($_POST['exerciseName' . $i]);
                $inputData['exerciseType' . $i] = trim($_POST['exerciseType' . $i]);
                $inputData['exerciseTarget' . $i] = trim($_POST['exerciseTarget' . $i]);
                $inputData['exerciseNum' . $i] = trim($_POST['exerciseNum' . $i]);
                $errors['exerNameErr' . $i] = '';
                $errors['exerTypeErr' . $i] = '';
                $errors['exerTargetErr' . $i] = '';
                $errors['exerNumErr' . $i] = '';
            };

            $data = [
                'numOfExer' => trim($_POST['numOfExer']),
                'exercises' => $exercises,
                'inputData' => $inputData,
                'errors' => $errors
            ];

            // VALIDATION . . .
            // check if "number of exercises" is between 1 - 15
            if ($data['numOfExer'] <= 0 || $data['numOfExer'] > EXERCISEMAX) {
                $data['numOfExer'] = 1;
                $data['errors']['numOfExerErr'] = 'Invalid number of exercises. Must be between 1 to ' . EXERCISEMAX;
            }

            // check if mod title and description are filled in AND in the min/max length range
            if (empty($inputData['title'])) {
                $data['errors']['titleErr'] = 'Please fill in title';
            } else if (strlen($inputData['title']) < 3 || strlen($inputData['title']) > 80) {
                $data['errors']['titleErr'] = 'Title must be between 3 - 80 characters';
            }

            if ($inputData['title'])
                if (empty($inputData['desc'])) {
                    $data['errors']['descErr'] = 'Please fill in description';
                } else if (strlen($inputData['desc']) < 3 || strlen($inputData['desc']) > 200) {
                    $data['errors']['descErr'] = 'Title must be between 3 - 200 characters';
                }

            // go through all exercises based off "number of exercises" and validate
            for ($i = 1; $i <= $data['numOfExer']; $i++) {
                if (empty($inputData['exerciseName' . $i])) {
                    $data['errors']['exerNameErr' . $i] = 'Please enter exercise name';
                } else if (strlen($inputData['exerciseName' . $i])  < 3 || strlen($inputData['exerciseName' . $i]) > 80) {
                    $data['errors']['exerNameErr' . $i] = 'Exercise name must be between 3 - 80 characters';
                }

                if (empty($inputData['exerciseNum' . $i])) {
                    $data['errors']['exerNumErr' . $i] = 'Please enter amount of sets or time for exercise';
                }
                if ($inputData['exerciseType' . $i] != 'cardio' && $inputData['exerciseNum' . $i] > 10) {
                    $data['errors']['exerNumErr' . $i] = 'Can not go past the set maximum of 10';
                }
            }

            // Once form has passed VALIDATION . . .
            // new array to push data into DB
            $modData = array();
            $modData['mod_title'] = $data['inputData']['title'];
            $modData['mod_desc'] = $data['inputData']['desc'];
            $modData['created_by_id'] = $_SESSION['user_id'];
            $modData['created_at'] = date('Y-m-d H:i:s');
            if (!array_filter($data['errors'])) {
                // Cycle through all exercises in mod
                for ($i = 1; $i <= $data['numOfExer']; $i++) {
                    $exists = false;
                    // Checking if each exercise is existing in DB already
                    foreach ($exercises as $exer) {
                        if ($exer->exercise_name == $inputData['exerciseName' . $i]) {
                            // if it does exist, insert existing exercise info
                            $exists = true;
                            $modData['exer' . $i . '_id'] = $exer->exercise_id;
                            $modData['exer' . $i . '_num'] = $data['inputData']['exerciseNum' . $i];
                        }
                    }
                    if ($exists == false) {
                        // if it is a new exercise, save in DB, then grab the new exercise's ID and set it as the exercise name
                        $newExerData = [
                            'exercise_name' => $data['inputData']['exerciseName' . $i],
                            'body_group' => $data['inputData']['exerciseTarget' . $i],
                            'exercise_type' => $data['inputData']['exerciseType' . $i]
                        ];

                        if ($this->exerciseModel->add($newExerData)) {
                            $newExercise = $this->exerciseModel->getExerciseByName($data['inputData']['exerciseName' . $i]);
                            $modData['exer' . $i . '_id'] = $newExercise->exercise_id;
                            $modData['exer' . $i . '_num'] = $data['inputData']['exerciseNum' . $i];
                        }
                    }
                }


                if ($this->modModel->addMod($modData, $data['numOfExer'])) {
                    flash('add_mod_success', 'Mod successfully added!');
                    redirect('mods');
                }
            } else {
                $this->view('mods/add', $data);
            }
        } else {

            // generate input fields to store form data after POST . . . 
            // . . . ALSO generate error fields for all input fields
            $inputData = array();
            $errors = array();

            $inputData['title'] = '';
            $inputData['desc'] = '';
            $errors['titleErr'] = '';
            $errors['descErr'] = '';
            $errors['numOfExerErr'] = '';
            for ($i = 1; $i <= EXERCISEMAX; $i++) {
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
