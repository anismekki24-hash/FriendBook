<?php
session_start();

use Models\Authentication;
use Models\FriendDataSet;
use Models\Pagination;

require_once "Models/Authentication.php";
require_once "Models/FriendDataSet.php";
require_once "Models/Pagination.php";


$view = new stdClass();
$view->pageTitle = "Friends";
$view->notUser = "Login to see friends list";
$friendDataSet = new FriendDataSet();
$view->friendDataSet = $friendDataSet->fetchFriends($_GET['id'] ?? null);
$currentUser = Authentication::currentUser();

if (count($view->friendDataSet) == 0) {
    $view->dbMessage = "No friends found";
} else {
    $view->dbMessage = count($view->friendDataSet) . " friend(s)";
}

//handling request
if (\Models\Authentication::isLoggedIn()) {
    if (isset($_POST['sendRequest'])) {
        //checking if request exists
        if (!($friendDataSet->requestExist($currentUser->getId(), $_POST['receiver_id']))) {
            $friendDataSet->sendFriendRequest($currentUser->getId(), $_POST['receiver_id']);
//    header("Location: friends.php?id=" . $_POST['receiver_id']);
            header('Location: index.php?msg=Request sent');
            exit;
        }
    }

    if (isset($_POST['acceptRequest'])) {
        $friendDataSet->acceptFriendRequest($currentUser->getId(), $_POST['friend_id']);
        header('Location: friends.php?id=' . $currentUser->getId() . '&Request_Accepted');
        exit;
    }

    if (isset($_POST['rejectRequest'])) {
        $friendDataSet->rejectFriendRequest($currentUser->getId(), $_POST['friend_id']);
        header('Location: friends.php?id=' . $currentUser->getId() . '&Request_Rejected');
        exit;
    }

    // Fetch pending friend requests only when viewing their own friends list
    if (isset($_GET['id']) && $_GET['id'] == $currentUser->getId()) {
        $view->pendingRequests = $friendDataSet->checkFriendRequests($currentUser->getId());
    }
}
require_once "Views/friends.phtml";
