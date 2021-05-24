<?php

class Mod
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getSelections(){
        $selections = 'mod_id, mod_title, mod_desc, created_by_id, mods.created_at AS modCreated, users.user_id AS "userId", users.user_first AS "userFirst", users.user_last AS "userLast"';
        for($i = 1; $i <= EXERCISEMAX; $i++){
            //create variables for each exercise
            $selections = $selections . ', mods.exer'.$i.'_id AS "exer'.$i.'Id", mods.exer'.$i.'_num, exer'.$i.'.exercise_name AS "exer'.$i.'Name", exer'.$i.'.body_group AS "exer'.$i.'Target", exer'.$i.'.exercise_type AS "exer'.$i.'Type"';
        }
        return $selections;
    }

    public function getJoins(){
        $joins = ' INNER JOIN users ON mods.created_by_id = users.user_id ';
        for($i = 1; $i <= EXERCISEMAX; $i++){
            // Create join for each exercise
            $joins = $joins . 'LEFT JOIN exercises AS exer'.$i.' ON mods.exer'.$i.'_id = exer'.$i.'.exercise_id ';
        }
        return $joins;
    }

    public function getPaginatedModsRecent($offset, $recordsPerPage)
    {
        $statement = 'SELECT '. $this->getSelections() . ' FROM mods ' . $this->getJoins() . 'ORDER BY mods.created_at DESC LIMIT ' . $offset . ', ' . $recordsPerPage;

        $this->db->query($statement);

        $results = $this->db->resultSet();
        return $results;
    }

    public function getPaginatedModsUser($offset, $recordsPerPage, $userId)
    {
        $statement = 'SELECT '. $this->getSelections() . ' FROM mods ' . $this->getJoins() . 'WHERE mods.created_by_id = :user_id ORDER BY mods.created_at DESC LIMIT ' . $offset . ', ' . $recordsPerPage;

        $this->db->query($statement);
        $this->db->bind(':user_id', $userId);
        $results = $this->db->resultSet();
        return $results;
    }

    public function getUserMods($data){
        $this->db->query('SELECT * FROM mods WHERE created_by_id = :user_id');

        $this->db->bind(':user_id', $data['userId']);

        return $this->db->resultSet();
    }

    public function getModCount()
    {
        $res = $this->db->query('SELECT COUNT(*) FROM mods');
        $count = $this->db->selectCount();
        return $count;
    }

    public function getUserModCount($userId)
    {
        $res = $this->db->query('SELECT COUNT(*) FROM mods WHERE created_by_id = :user_id');
        $this->db->bind(':user_id', $userId);
        $count = $this->db->selectCount();
        return $count;
    }

    public function findModById($modId){
        $this->db->query('SELECT '. $this->getSelections() .' FROM mods '. $this->getJoins() .' WHERE mod_id = :mod_id');
        $this->db->bind(':mod_id', $modId);
        return $this->db->single();
    }

    public function addMod($data, $numOfExer)
    {
        $exerciseParams = '';
        $boundExercises = '';

        for ($i = 1; $i <= $numOfExer; $i++) {
            if ($i != $numOfExer) {
                $exerciseParams = $exerciseParams . 'exer' . $i . '_id, exer' . $i . '_num, ';
                $boundExercises = $boundExercises . ':exer' . $i . '_id, :exer' . $i . '_num, ';
            } else {
                $exerciseParams = $exerciseParams . 'exer' . $i . '_id, exer' . $i . '_num';
                $boundExercises = $boundExercises . ':exer' . $i . '_id, :exer' . $i . '_num';
            }
        }

        $this->db->query('INSERT INTO mods (
            mod_title, mod_desc, 
            created_by_id, created_at,' . $exerciseParams . ') 
            VALUES
            (:mod_title, :mod_desc, 
            :created_by_id, :created_at,' . $boundExercises . ')');

        // Bind values
        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
