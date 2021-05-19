<?php

class Workouts extends Controller
{

    public function __construct()
    {
        $this->modModel = $this->model('Mod');
        $this->workoutModel = $this->model('Workout');
        $this->savedModel = $this->model('SavedMod');

        // check if logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
    }

    public function index(){
        redirect('workouts/schedule');
    }

    public function schedule($startingDate = null){

        $closeData = [
            'date' => date('m/d/Y'),
            'userId' => $_SESSION['user_id']
        ];

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
            }

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


}