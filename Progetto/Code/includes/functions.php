<?php

function isUserLoggedIn() {
    return isset($_SESSION['user']);
}

function getLoggedUser() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}

function registerLoggedUser($user) {
    $_SESSION['user'] = $user;
}

?>