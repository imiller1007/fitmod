<?php

class SavedMod
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getModsSavedByUser($data)
    {
        $this->db->query('SELECT sm.saved_datetime, m.mod_id, m.mod_title, m.mod_desc, m.created_by_id, m.created_at  
        FROM saved_mods AS sm 
        LEFT JOIN mods AS m ON sm.mod_id = m.mod_id 
        WHERE user_id = :user_id 
        ORDER BY saved_datetime DESC');

        $this->db->bind(':user_id', $data['userId']);

        $results = $this->db->resultSet();
        return $results;
    }

    public function getUserAndSavedModsAlph($data){
        $this->db->query('SELECT mod_id, mod_title FROM mods WHERE mods.created_by_id = :user_id
        UNION
        SELECT sm.mod_id AS mod_id, m.mod_title AS mod_title FROM saved_mods AS sm
        LEFT JOIN mods AS m ON sm.mod_id = m.mod_id
        WHERE sm.user_id = :user_id
        ORDER BY mod_title ASC');

        $this->db->bind(':user_id', $data['userId']);

        return $this->db->resultSet();
    }

    public function getSavedModByModId($modId, $userId){
        $this->db->query('SELECT * FROM saved_mods WHERE user_id = :user_id AND mod_id = :mod_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':mod_id', $modId);
        $results = $this->db->single();
        return $results;
    }

    public function saveMod($modId, $userId)
    {
        $this->db->query('INSERT INTO saved_mods (mod_id, user_id) VALUES (:mod_id, :user_id)');

        $this->db->bind(':mod_id', $modId);
        $this->db->bind(':user_id', $userId);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function unsaveMod($modId, $userId)
    {
        $this->db->query('DELETE FROM saved_mods WHERE mod_id = :mod_id AND user_id = :user_id');

        // Bind  values
        $this->db->bind(':mod_id', $modId);
        $this->db->bind(':user_id', $userId);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}