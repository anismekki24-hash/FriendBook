<?php
session_start();

use Models\Authentication;

require_once "Models/Authentication.php";
require_once("Models/UserDataSet.php");

$view = new stdClass();
$view->pageTitle = 'Login';
$userDataSet = new UserDataSet();

if (isset($_POST['LoginButton'])) {
    //extract the user input
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $userDataSet->fetchUser($username);
    //verify credentials
    if ($user && password_verify($password, $user->getPassword())) {
        Authentication::login($user);
        header('Location: index.php');
    } else {
        $view->wrong = "Wrong Credentials";
    }
}
if (isset($_GET['LogoutButton'])) {
    Authentication::logout();
    header("Location: login.php");
    exit();
}

require_once("Views/login.phtml");

//            echo password_hash('123456', PASSWORD_DEFAULT);
//            echo password_hash('x7Yk9Pq2', PASSWORD_DEFAULT);
//            echo password_hash('Qw3Er7Ty', PASSWORD_DEFAULT);
//            echo password_hash('mN8bV2cX', PASSWORD_DEFAULT);
//            echo password_hash('zX6cVb1n', PASSWORD_DEFAULT);
//            echo password_hash('L9kP2wQa', PASSWORD_DEFAULT);
//            echo password_hash('Hj4K7Lm2', PASSWORD_DEFAULT);
//            echo password_hash('U8iOp3As', PASSWORD_DEFAULT);
//            echo password_hash('T6yHg9Fd', PASSWORD_DEFAULT);
//            echo password_hash('R5fGh2Jk', PASSWORD_DEFAULT);
//            echo password_hash('658dfsj', PASSWORD_DEFAULT);
//            echo password_hash('slkdjdf', PASSWORD_DEFAULT);
//            echo password_hash('password', PASSWORD_DEFAULT);