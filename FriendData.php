<?php

namespace Models;
class FriendData
{
    //fields
    protected mixed $id, $friend1, $friend2, $status, $username, $email, $photo;

    public function __construct($dbRow)
    {
        $this->id = $dbRow['id'];
        $this->friend1 = $dbRow['friend1'];
        $this->friend2 = $dbRow['friend2'];
        $this->status = $dbRow['status'];
        $this->username = $dbRow['username'];
        $this->email = $dbRow['email'];
        $this->photo = $dbRow['photo'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFriend1()
    {
        return $this->friend1;
    }

    public function getFriend2()
    {
        return $this->friend2;
    }

    public function getStatus()
    {
        return $this->status;
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
}