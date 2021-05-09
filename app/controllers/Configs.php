<?php

class Configs extends Controller
{

    public function __construct()
    {
        // if (!isLoggedIn()) {
        //     redirect('users/login');
        // }

        $this->exerciseModel = $this->model('Exercise');
    }

    public function index()
    {

        $data = [];

        $this->view('pages/index', $data);
    }

    public function testAutocomplete()
    {
        $exercises = $this->exerciseModel->getExercises();

        $data = ['exercises' => $exercises];

        $this->view('configs/testAutocomplete', $data);
    }

    public function testExerciseSave()
    {
        $exercises = $this->exerciseModel->getExercises();

        $exerArr = ['Peepoo', 'Bear Walk', 'teetees', 'Barbell Lunges'];

        for ($i = 0; $i < count($exerArr); $i++) {
            $exists = false;
            // Checking if each exercise is existing in DB already
            foreach ($exercises as $exer) {
                if ($exer->exercise_name == $exerArr[$i]) {
                    // if it does exist, insert existing exercise info
                    $exists = true;
                }
            }
            echo $exists == true ? "It's in the DB <br>" : "It's not in there <br>";
        }
    }

    // This is a one-time use function to transfer exercise data from wger.de API
    public function TransferExercisesToTable()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $exercises = wgerCall('exercise');
            $categories = wgerCall('exercisecategory');
            $equipment = wgerCall('equipment');

            // Loop through exercises
            for ($i = 0; $i < $exercises->count; $i++) {

                // Get Id for body group category for exercise
                $categoryNum = $exercises->results[$i]->category;
                $categoryId = array_search($categoryNum, array_column($categories->results, 'id'));

                // exercise type: weight, bodyWeight, noWeight / Based off equipment used for exercise
                $exerciseType = '';

                switch ($exercises->results[$i]->equipment) {

                        // case: no equipment used (no weights involved)
                    case empty($exercises->results[$i]->equipment):
                        $exerciseType = 'noWeight';
                        break;

                        // case: if any of these equipment ID's (Barbell, Dumbbell, Kettlebell, EZ-Bar) appear, it involves adjusted weight
                    case array_search(1, $exercises->results[$i]->equipment) ||
                        array_search(3, $exercises->results[$i]->equipment) ||
                        array_search(10, $exercises->results[$i]->equipment) ||
                        array_search(2, $exercises->results[$i]->equipment):
                        $exerciseType = 'adjustedWeight';
                        break;

                        // default case: any other exercises involve using body weight
                    default:
                        $exerciseType = 'bodyWeight';
                }

                $data = [
                    'exercise_name' => $exercises->results[$i]->name,
                    'body_group' => $categories->results[$categoryId]->name,
                    'exercise_type' => $exerciseType
                ];

                if (!$this->exerciseModel->add($data)) {
                    die('Something went wrong');
                }
            }

            $data = ['transferred' => true];
            flash('transfer_success', 'Transfer Completed!');
            $this->view('configs/transferExercisesToTable', $data);
        } else {
            $transferred = false;

            // Check if exercises have been transferred...
            if ($this->exerciseModel->getExercises() != null) {
                $transferred = true;
            }

            $data = [
                'transferred' => $transferred
            ];

            $this->view('configs/transferExercisesToTable', $data);
        }
    }
}
