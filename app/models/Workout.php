<?php

class Workout
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function add($data)
    {
        $this->db->query('INSERT INTO workouts (user_id, mod_id, workout_date) VALUES (:user_id, :mod_id, :workout_date)');
        // Bind values
        $this->db->bind(':user_id,', $data['user_id']);
        $this->db->bind(':mod_id', $data['mod_id']);
        $this->db->bind(':workout_date', $data['workout_date']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getWorkoutsForWeek($data){

        $endingDate = date('Y-m-d H:i:s', strtotime("+7 day", strtotime($data['startingDate'])));

        $selectStatement = '*';
        $joinStatement = '';
        for($i = 1; $i <= EXERCISEMAX; $i++){
            $joinStatement = $joinStatement . 'LEFT JOIN exercises AS e'.$i.' ON exer'.$i.'_id = e'.$i.'.exercise_id ';
            $selectStatement = $selectStatement . ', m.exer'.$i.'_num, e'.$i.'.exercise_name AS "exer'.$i.'Name", e'.$i.'.exercise_type AS "exer'.$i.'Type"';
        }

        $this->db->query('SELECT ' . $selectStatement . ', s.exercise_id AS setExerId FROM workouts AS w LEFT JOIN mods AS m ON w.mod_id = m.mod_id LEFT JOIN sets as s ON s.workout_id = w.workout_id ' . $joinStatement .
         'WHERE w.user_id = :user_id AND workout_date >= :starting_date AND workout_date <= :ending_date ORDER BY workout_date ASC');

        // Bind values
        $this->db->bind(':user_id', $data['userId']);
        $this->db->bind(':starting_date', $data['startingDate']);
        $this->db->bind(':ending_date', $endingDate);

        
        return $this->db->resultSet();
    }

    public function getModsByRecentWorkouts($data){
        $this->db->query('SELECT m.mod_id, m.mod_title FROM `workouts` AS w 
        LEFT JOIN mods as m ON m.mod_id = w.mod_id
        WHERE user_id = :user_id AND w.mod_id != 0 
        GROUP BY w.mod_id 
        ORDER BY assign_date 
        DESC LIMIT 7;');

        $this->db->bind(':user_id', $data['userId']);

        return $this->db->resultSet();
    }

    public function closeOldWorkouts($data){

        $this->db->query('UPDATE workouts SET status = "closed" WHERE workout_date < :cutoff_date AND user_id = :user_id');

        // Bind values
        $this->db->bind(':cutoff_date', date('Y-m-d', strtotime($data['date'])));
        $this->db->bind(':user_id', $data['userId']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

}