<?php

// load required classes

use Models\Authentication;
use Models\FriendDataSet;
use Models\Pagination;

require_once('Models/Authentication.php');
require_once('Models/UserDataSet.php');
require_once('Models/FriendDataSet.php');
require_once('Models/Pagination.php');
//require_once('populatePfp.php');
$userDataSet = new UserDataSet();
$friendDataSet = new FriendDataSet();
session_start();

// make a view class
$view = new stdClass();
$view->pageTitle = 'Users Information Page';
$view->currentUser = Authentication::currentUser();
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$pagination = new Pagination($currentPage, 10, $userDataSet->getTotalUserCount());
$view->friendDataSet = $friendDataSet;
$view->userDataSet = $userDataSet->fetchUsersByPage($pagination);
$view->pagination = $pagination;
$view->pages = $pagination->getTotalPages();
$view->friendDataSet = $friendDataSet;
$view->relationships = [];


if (isset($_GET['LogoutButton'])) {
    Authentication::logout();
    header("Location: login.php");
    exit();
}

// send a results count to the view to show how many results were retrieved
if (count($view->userDataSet) == 0) {
    $view->dbMessage = "No results";
} else {
    $view->dbMessage = count($view->userDataSet) . " result(s)";
}

if (isset($_GET['searchBar'])) {
    $view->userDataSet = $userDataSet->searchUsers($_GET['searchBar']);
    if (count($view->userDataSet) == 0) {
        $view->dbMessage = "No results";
    } else {
        $view->pageTitle = "Search Results for '" . htmlspecialchars($_GET['searchBar'], ENT_QUOTES, 'UTF-8') . "'";
        $view->dbMessage = count($view->userDataSet) . " result(s)";
    }
}

// include the view
require_once('Views/index.phtml');