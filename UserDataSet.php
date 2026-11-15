<?php

use Models\Database;

require_once('Database.php');
require_once('UserData.php');

class UserDataSet
{
    protected ?Database $_dbInstance;
    protected PDO $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetchUser($username): ?UserData
    {
        $sqlQuery = 'SELECT * FROM users WHERE username = :username;'; //prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->execute([':username' => $username]); // execute the PDO statement
        $user = $statement->fetch(PDO::FETCH_ASSOC); // return the matching row

        if ($user) {
            return new UserData($user);
        }
        return null;


    }

    public function fetchUserById($id): ?UserData
    {
        $sqlQuery = 'SELECT * FROM users WHERE id = :id;'; //prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->execute([':id' => $id]); // execute the PDO statement
        $user = $statement->fetch(PDO::FETCH_ASSOC); // return the matching row


        if ($user) {
            return new UserData($user);
        }
        return null;


    }

    public function fetchAllUsers(): array
    {
        $sqlQuery = 'SELECT * FROM users';

        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        // loop through and read the results of the query and cast
        // them into a matching object
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    public function updatePhoto($id, $photoUrl): void
    {
        $sqlQuery = 'UPDATE users SET photo = :photo WHERE id = :id;';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([':photo' => $photoUrl, ':id' => $id]);
    }

    public function searchUsers($username): array
    {
        $sqlQuery = 'SELECT * 
        FROM users 
        WHERE username LIKE :search;';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([':search' => "$username%"]);
        $dataSet = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;

    }


    public function updateProfile($id, $username, $email, $password)
    {
        $sqlQuery = 'UPDATE users 
        SET username =:username, email = :email, password = :password 
        WHERE id = :id;';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([':id' => $id, ':email' => $email, ':password' => $password, ':username' => $username]);
    }

    public function fetchUsersByPage(\Models\Pagination $pagination): array
    {
        $sqlQuery = 'SELECT * FROM users LIMIT :offset, :limit;';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(':offset', $pagination->getOffset(), PDO::PARAM_INT);
        $statement->bindValue(':limit', $pagination->getLimit(), PDO::PARAM_INT);
        $statement->execute();
        $dataSet = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $dataSet[] = new UserData($row);
        }
        return $dataSet;
    }

    public function getTotalUserCount(): int
    {
        $sqlQuery = 'SELECT COUNT(*) FROM users;';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();

        return (int)$statement->fetchColumn();
    }
}