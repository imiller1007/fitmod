<?php

    class Mod{
        private $db;

        public function __construct(){
            $this->db = new Database;
        }

        public function getMods(){
            $this->db->query('SELECT *
                              FROM mods
                              INNER JOIN users
                              ON mods.created_by_id = users.user_id
                              ORDER BY mods.created_at DESC
                              ');
            $results = $this->db->resultSet();

            return $results;
        }
    }