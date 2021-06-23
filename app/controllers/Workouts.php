<?php

class Workouts extends Controller
{

    public function __construct()
    {
        $this->modModel = $this->model('Mod');
        $this->workoutModel = $this->model('Workout');
        $this->savedModel = $this->model('SavedMod');
        $this->setModel = $this->model('Set');

        // check if logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        setWorkoutStatusSession($this->workoutModel->getCurrentWorkout($_SESSION['user_id']));
    }

    function getSundayOfWeek($sentDate){
        $dayOfWeek = strtolower(date("l", strtotime($sentDate)));
        while ($dayOfWeek != 'sunday') {
            $dayOfWeek = strtolower(date('l', strtotime('-1 day', strtotime($dayOfWeek))));
            $sentDate = date('m/d/Y', strtotime('-1 day', strtotime($sentDate)));
        };
        return $sentDate;
    }

    public function index(){
        redirect('workouts/schedule');
    }

    public function schedule($startingDate = null){

        // data to close old workouts
        $closeData = [
            'date' => date('m/d/Y'),
            'userId' => $_SESSION['user_id']
        ];

        // close any workouts that are passed current day
        if ($this->workoutModel->closeOldWorkouts($closeData)) {
            if ($startingDate == null || strtotime($startingDate) == false) {
                $startingDate = date('m/d/Y');
            }

            // get day of week of current date
            $dayOfWeek = strtolower(date("l", strtotime($startingDate)));

            // cycle to get to the beginning (sunday) of the week
            while ($dayOfWeek != 'sunday') {
                $dayOfWeek = strtolower(date('l', strtotime('-1 day', strtotime($dayOfWeek))));
                $startingDate = date('m/d/Y', strtotime('-1 day', strtotime($startingDate)));
            };

            $workoutData = [
                'userId' => $_SESSION['user_id'],
                'startingDate' => $startingDate
            ];

            $weekData = $this->workoutModel->getWorkoutsForWeek($workoutData);

            $modSelects = [
                'recent' => $this->workoutModel->getModsByRecentWorkouts($workoutData),
                'user' => $this->modModel->getUserMods($workoutData),
                'saved' => $this->savedModel->getModsSavedByUser($workoutData),
                'all' => $this->savedModel->getUserAndSavedModsAlph($workoutData)
            ];

            $data = [
                'startingDate' => $startingDate,
                'weekData' => $weekData,
                'modSelects' => $modSelects
            ];

            $this->view('workouts/schedule', $data);
        }

    }

    public function results($startingDate = null){

         // data to close old workouts
         $closeData = [
            'date' => date('m/d/Y'),
            'userId' => $_SESSION['user_id']
        ];

        // close any workouts that are passed current day
        if ($this->workoutModel->closeOldWorkouts($closeData)) {
            if ($startingDate == null || strtotime($startingDate) == false) {
                $startingDate = date('m/d/Y');
            }

            // get day of week of current date
            $dayOfWeek = strtolower(date("l", strtotime($startingDate)));

            // get starting date
            $startingDate = $this->getSundayOfWeek($startingDate);

            if (date('m/d/Y', strtotime($startingDate)) >= date('m/d/Y', strtotime('+7 days', strtotime($this->getSundayOfWeek(date('m/d/Y')))))) {
                $startingDate = date('m/d/Y', strtotime($this->getSundayOfWeek(date('m/d/Y'))));
            }

            $workoutData = [
                'userId' => $_SESSION['user_id'],
                'startingDate' => $startingDate
            ];

            $weekData = $this->workoutModel->getWorkoutsForWeek($workoutData);

            $data = [
                'startingDate' => $startingDate,
                'weekData' => $weekData
            ];

            $this->view('workouts/results', $data);
        }
    }

    public function active(){
        // check if workout is assigned for today
        $dbData = [
            'workoutDate' => date('Y-m-d'),
            'userId' => $_SESSION['user_id']
        ];
        $workout = $this->workoutModel->getWorkoutByDate($dbData);
        if($workout == null){
            flash('assign_mod_success', 'Assign a workout mod for today before starting workout', 'alert alert-danger');
            redirect('workouts/schedule');
        }
        if($workout->mod_id == 0){
            flash('assign_mod_success', 'Rest Day was assigned for today. Enjoy the R & R! <i class="fas fa-mug-hot"></i>', 'alert alert-info');
            redirect('workouts/schedule');
        }


        // mod assigned for workout
        $modUsed = $this->modModel->findModById($workout->mod_id);

        // calculate total sets for mod
        $totalSetsForMod = 0;
        for($i = 1; $i <= EXERCISEMAX; $i++){

            $exerNum = 'exer' . $i . '_num'; 
            $exerType = 'exer' . $i . 'Type';

            if($modUsed->$exerType != 'cardio'){
                // add total sets of exercise to total sets for mod
                $totalSetsForMod = $totalSetsForMod + $modUsed->$exerNum;
            }else{
                // add 1 to the total if it is a cardio exercise
                $totalSetsForMod = $totalSetsForMod + 1;
            }
        }
        
        // set info for each set
        $setInfo = $this->setModel->getAllSetsForWorkout($workout->workout_id);

        // if sets are already present, make sure to set workout as started
        if($workout->status == 'open' && $setInfo != null){
            $this->workoutModel->startWorkout($workout->workout_id);
        }

        // amount of total sets done
        $setCount = $this->setModel->getSetCountForWorkout($workout->workout_id);

        if($setCount == $totalSetsForMod){
            $this->workoutModel->closeWorkout($workout->workout_id);
        }

        $data = [
            'workoutId' => $workout->workout_id,
            'modUsed' => $modUsed,
            'totalSetsForMod' => $totalSetsForMod,
            'setInfo' => $setInfo,
            'setCount' => $setCount
        ];

        $this->view('workouts/active', $data);
    }

    public function logExerciseAjax(){
         // check if logged in
         if (!isLoggedIn()) {
            redirect('users/login');
        }

        // check if this method was called from a post call
        if (!isset($_POST['exercise_id'])) {
            redirect('workouts/schedule');
        }

        $dbData = [
            'userId' => $_SESSION['user_id'],
            'workout_id' => $_POST['workout_id'],
            'exercise_id' => $_POST['exercise_id'],
            'exercise_value' => $_POST['exercise_value'],
            'exercise_weight' => $_POST['exercise_weight']
        ];

        if($_POST['exerType'] == 'adjWt'){
            if($this->setModel->addAdjWtSet($dbData)){
                $message = 'exercise added!';
                $response = array();
                $response['success'] = true;
                $response['message'] = $message;
                echo json_encode($response);
            }else{
                $err = 'Something went wrong while adding exercise';
                die(error($err));
            }
        }else{
            if($this->setModel->addSet($dbData)){
                $message = 'exercise added!';
                $response = array();
                $response['success'] = true;
                $response['message'] = $message;
                echo json_encode($response);
            }else{
                $err = 'Something went wrong while adding exercise';
                die(error($err));
            }
        }

    }

    public function editExerciseAjax()
    {
        // check if logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // check if this method was called from a post call
        if (!isset($_POST['set_id'])) {
            redirect('workouts/schedule');
        }

        $dbData = [
            'set_id' => $_POST['set_id'],
            'set_value' => $_POST['set_value'],
            'set_weight' => $_POST['set_weight']
        ];

        if($_POST['exerType'] == 'adjWt'){
            if($this->setModel->updateAdjSet($dbData)){
                $message = 'exercise added!';
                $response = array();
                $response['success'] = true;
                $response['message'] = $message;
                echo json_encode($response);
            }else{
                $err = 'Something went wrong while editing exercise';
                die(error($err));
            }
        }else{
            if($this->setModel->updateSet($dbData)){
                $message = 'exercise added!';
                $response = array();
                $response['success'] = true;
                $response['message'] = $message;
                echo json_encode($response);
            }else{
                $err = 'Something went wrong while editing exercise';
                die(error($err));
            }
        }

    }

    public function assignModAjax()
    {
        // check if logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // check if this method was called from a post call
        if (!isset($_POST['weekday']) || !isset($_POST['modId'])) {
            redirect('workouts/schedule');
        }

        // set vars based off post
        $modId = $_POST['modId'];
        $weekday = $_POST['weekday'];

        // ID 0 represents rest day, else check if ID exists in DB
        if($modId != 0){
            if (!$this->modModel->findModById($modId)) {
            $err = '<strong>Error:</strong> Mod does not exist';
            flash('assign_mod_success', $err, 'alert alert-danger');
            die(error($err));
            }
        }
        // validate date given
        if (strtotime($weekday) == false){
            $err = '<strong>Error:</strong> not a date';
            flash('assign_mod_success', $err, 'alert alert-danger');
            die(error($err));
        }elseif(date('m/d/Y', strtotime($weekday)) < date('m/d/Y')){
            $err = '<strong>Error:</strong> Can not assign mod to date that has already passed';
            flash('assign_mod_success', $err, 'alert alert-danger');
            die(error($err));
        }

        $dbData = [
            'workoutDate' => date('Y-m-d', strtotime($weekday)),
            'userId' => $_SESSION['user_id']
        ];

        // grab assigned workout for that date if exists
        $existingAssign = $this->workoutModel->getWorkoutByDate($dbData);

        $dbData['modId'] = $modId;
        // if it does exist . . .
        if($existingAssign != null){
            // . . . and status is open . . .
            if($existingAssign->status == 'open'){
                // update
                if($this->workoutModel->updateWeekdayWithNewWorkout($dbData)){
                    flash('assign_mod_success', 'Workout successfully changed!');
                    $message = 'Workout updated!';
                    $response = array();
                    $response['success'] = true;
                    $response['message'] = $message;

                    echo json_encode($response);
                }else{
                    $err = '<strong>Error:</strong> Something went wrong while updating the workout';
                    flash('assign_mod_success', $err, 'alert alert-danger');
                    die(error($err));
                }
            }else{
                // error
                $err = '<strong>Error:</strong> Can not update an assignment that is not open';
                flash('assign_mod_success', $err, 'alert alert-danger');
                die(error($err));
            }
        }else{
            // assign to unassigned date
            if($this->workoutModel->add($dbData)){
                flash('assign_mod_success', 'Workout successfully assigned!');
                $message = 'Mod assigned!';
                $response = array();
                $response['success'] = true;
                $response['message'] = $message;

                echo json_encode($response);
            }else{
                $err = '<strong>Error:</strong> Something went wrong while assigning the workout';
                flash('assign_mod_success', $err, 'alert alert-danger');
                die(error($err));
            }
        }
    }

}