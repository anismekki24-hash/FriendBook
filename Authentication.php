<?php

namespace Models;

require_once 'UserData.php';
require_once 'UserDataSet.php';

use UserData;
use UserDataSet;

class Authentication
{
    private static ?UserData $user = null;

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['id']);
    }

    public static function currentUser(): ?UserData
    {
        if (!self::isLoggedIn()) return null;
        if (self::$user === null) {
            self::$user = (new UserDataSet())->fetchUserById(self::id());
        }
        return self::$user;
    }

    public static function id(): ?int
    {
        return self::isLoggedIn() ? (int)$_SESSION['id'] : null;
    }

    public static function username(): ?string
    {
        return self::isLoggedIn() ? $_SESSION['username'] : null;
    }

    public static function login(UserData $user): void
    {
        $_SESSION['id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        self::$user = $user;
    }

    public static function logout(): void
    {
        session_destroy();
        self::$user = null;
    }

}