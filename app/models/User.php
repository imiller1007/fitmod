<?php

class User {

    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Register User
    public function register($data){
        $this->db->query('INSERT INTO users (user_email, user_first, user_last, password) VALUES (:user_email, :user_first, :user_last, :password)');
        // Bind values
        $this->db->bind(':user_email', $data['user_email']);
        $this->db->bind(':user_first', $data['user_first']);
        $this->db->bind(':user_last', $data['user_last']);
        $this->db->bind(':password', $data['password']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password){
        $this->db->query('SELECT * FROM users WHERE user_email = :email');
        // Bind values
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        $hashed_password = $row->password;
        if(password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Find user by email
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE user_email = :user_email');
        // Bind values
        $this->db->bind(':user_email', $email);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }





}