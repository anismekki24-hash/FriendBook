<?php

namespace Models;

use http\Env\Request;
use Models\Database;
use PDO;

require_once('Database.php');
require_once('FriendData.php');

class FriendDataSet
{
    protected ?Database $_dbInstance;
    protected PDO $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function fetchFriends($friend1): array
    {
        $sqlQuery = 'SELECT *
                     FROM users 
                     JOIN friends ON (friends.friend2 = users.id OR friends.friend1 = users.id)
                     WHERE (friends.friend1 = :id OR friends.friend2 = :id)
                     AND users.id != :id
                     AND status = 1'; //prepare a PDO statement
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement

        $statement->execute([':id' => $friend1]); // execute the PDO statement

        $dataSet = [];
        // loop through and read the results of the query
        while ($row = $statement->fetch()) {
            $dataSet[] = new FriendData($row);
        }

        return $dataSet;
    }

    public function sendFriendRequest($friend1, $friend2): void
    {

        //add new column with the new users
        $sqlQuery = 'INSERT INTO friends (friend1, friend2, status)
                     VALUES 
                     ( :friend1, :friend2, 0)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([':friend1' => $friend1, ':friend2' => $friend2]);
    }

    public function requestExist($friend1, $friend2): bool
    {
        $sqlQuery = 'SELECT *
                    FROM friends 
                    WHERE (friend1 = :friend1 AND friend2 = :friend2) AND status = 0';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([':friend1' => $friend1, ':friend2' => $friend2]);
        $row = $statement->fetch();
        if ($row) {
            return true;
        }
        return false;
    }

    public function areFriends($friend1, $friend2): bool
    {
        $sqlQuery = 'SELECT *
                    FROM friends 
                    WHERE ((friend1 = :f1 AND friend2 = :f2) OR (friend1 = :f3 AND friend2 = :f4)) AND status = 1';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([':f1' => $friend1, ':f2' => $friend2, ':f3' => $friend2, ':f4' => $friend1]);
        $row = $statement->fetch();
        if ($row) {
            return true;
        }
        return false;
    }

    public function acceptFriendRequest($friend1, $friend2): void
    {
        $sqlQuery = 'UPDATE friends
        SET status = 1
        WHERE (friend1 = :friend2 AND friend2 = :friend1 AND status = 0)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([':friend1' => $friend1, ':friend2' => $friend2]);
    }

    public function rejectFriendRequest($friend1, $friend2): void
    {
        $sqlQuery = 'DELETE from friends
        WHERE (friend1 = :friend2 AND friend2 = :friend1 AND status = 0)';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([':friend1' => $friend1, ':friend2' => $friend2]);
    }

    public function checkFriendRequests($receiver): array
    {
        $sqlQuery = 'SELECT friends.id, friends.friend1, friends.friend2, friends.status,
                           users.username, users.email, users.photo
        FROM friends
        JOIN users ON users.id = friends.friend1
        WHERE (friends.friend2 = :friend2 AND friends.status = 0)';

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute([':friend2' => $receiver]);
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FriendData($row);
        }
        return $dataSet;
    }

    public function fetchFriendsByPage($friend1, Pagination $pagination): array
    {
        $sqlQuery = 'SELECT *
                     FROM users 
                     JOIN friends ON (friends.friend2 = users.id OR friends.friend1 = users.id)
                     WHERE (friends.friend1 = :id OR friends.friend2 = :id)
                     AND users.id != :id
                     AND status = 1
                     LIMIT :limit OFFSET :offset';
        $statement = $this->_dbHandle->prepare($sqlQuery);

        $statement->bindValue(':id', $friend1, PDO::PARAM_INT);
        $statement->bindValue(':limit', $pagination->getLimit(), PDO::PARAM_INT);
        $statement->bindValue(':offset', $pagination->getOffset(), PDO::PARAM_INT);
        $statement->execute();

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new FriendData($row);
        }

        return $dataSet;
    }

    public function getTotalFriendCount($friend1): int
    {
        $sqlQuery = 'SELECT COUNT(*)
                     FROM friends
                     WHERE (friend1 = :id OR friend2 = :id)
                     AND status = 1';
        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->bindValue(':id', $friend1, PDO::PARAM_INT);
        $statement->execute();

        return (int)$statement->fetchColumn();
    }
}







