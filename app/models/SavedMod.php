<?php

class SavedMod
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getModsSavedByUser($userId)
    {
        $this->db->query('SELECT mod_id FROM saved_mods WHERE user_id = :user_id ORDER BY saved_datetime DESC');
        $this->db->bind(':user_id', $userId);
        $results = $this->db->resultSet();
        return $results;
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