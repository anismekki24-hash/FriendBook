<?php
require_once("Models\UserDataSet.php");
require_once("Models\Authentication.php");
session_start();

use Models\Authentication;

$view = new stdClass();
$view->pageTitle = "Update Profile Information";
$view->dbMessage = "";
$currentUser = Authentication::currentUser();
$userDataSet = new UserDataSet();
if ($currentUser && isset($_POST['updateInfo'])) {
    if (password_verify($_POST['oldPassword'], $currentUser->getPassword())) {
        if (!(password_verify($_POST['newPassword'], $currentUser->getPassword()))) {
            $userDataSet->updateProfile($currentUser->getId(), $_POST['username'], $_POST['email'], password_hash($_POST['newPassword'], PASSWORD_DEFAULT));
            $view->dbMessage = "Info updated successfully";
        } else {
            $view->dbMessage = "You can't re-use your old password.";
        }
    } else {
        $view->dbMessage = "Current password is incorrect";
    }
}
require_once('Views/profile.phtml');