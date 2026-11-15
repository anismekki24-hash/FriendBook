<?php

//namespace Models;
class UserData
{
    //fields
    protected mixed $id, $username, $email, $photo, $password;

    //constructor
    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->username = $dbRow['username'];
        $this->email = $dbRow['email'];
        $this->password = $dbRow['password'];
        $this->photo = $dbRow['photo'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getPassword()
    {
        return $this->password;
    }
}