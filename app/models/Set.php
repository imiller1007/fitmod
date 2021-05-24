<?php

class Set
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }
   
    public function getAllSetsForWorkout($workoutId){
        $this->db->query('SELECT * FROM `sets` AS s 
        LEFT JOIN exercises AS e ON s.exercise_id = e.exercise_id 
        WHERE s.workout_id = :workout_id');

        $this->db->bind(':workout_id', $workoutId);

        return $this->db->resultSet();
    }

    public function getSetCountForWorkout($workoutId){
        $this->db->query('SELECT COUNT(*) FROM `sets`
        WHERE workout_id = :workout_id');

        $this->db->bind(':workout_id', $workoutId);

        return $this->db->selectCount();
    }

    public function addSet($data){
        $this->db->query('INSERT INTO `sets` (workout_id, user_id, exercise_id, set_value) 
        VALUES (:workout_id, :user_id, :exercise_id, :set_value)');

        $this->db->bind(':workout_id', $data['workout_id']);
        $this->db->bind(':user_id', $data['userId']);
        $this->db->bind(':exercise_id', $data['exercise_id']);
        $this->db->bind(':set_value', $data['exercise_value']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function addAdjWtSet($data){
        $this->db->query('INSERT INTO `sets` (workout_id, user_id, exercise_id, set_value, set_weight) 
        VALUES (:workout_id, :user_id, :exercise_id, :set_value, :set_weight)');

        $this->db->bind(':workout_id', $data['workout_id']);
        $this->db->bind(':user_id', $data['userId']);
        $this->db->bind(':exercise_id', $data['exercise_id']);
        $this->db->bind(':set_value', $data['exercise_value']);
        $this->db->bind(':set_weight', $data['exercise_weight']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}    