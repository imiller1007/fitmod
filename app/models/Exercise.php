<?php

class Exercise
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function add($data)
    {
        $this->db->query('INSERT INTO exercises (exercise_name, body_group, exercise_type) VALUES (:exercise_name, :body_group, :exercise_type)');
        // Bind values
        $this->db->bind(':exercise_name', $data['exercise_name']);
        $this->db->bind(':body_group', $data['body_group']);
        $this->db->bind(':exercise_type', $data['exercise_type']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getExercises(){
        $this->db->query('SELECT * FROM exercises');
        return $this->db->resultSet();
    }

    public function getExerciseById($exerciseID){
        $this->db->query('SELECT * FROM exercises WHERE exercise_id = :exercise_id');
        $this->db->bind(':exercise_id', $exerciseID);
        return $this->db->single();
    }

    public function getExerciseByName($exerciseName){
        $this->db->query('SELECT * FROM exercises WHERE exercise_name = :exercise_name');
        $this->db->bind(':exercise_name', $exerciseName);
        return $this->db->single();
    }
}
