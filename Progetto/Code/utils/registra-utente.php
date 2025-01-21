<?php
require_once("../includes/bootstrap.php");

if (!isUserLoggedIn() && !isAdminLoggedIn()) {

    if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["admin"])) {
        //c'è più di uno user con la stessa email
        if (count($dbh->checkUsermail($_POST["email"]))) {
            // Registration failed
            $templateParams["error"] = "Un account con questa email è già stato registrato";
            echo "Un account con questa email è già stato registrato";
        } 
        else {
            $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
            if ($_POST["admin"] == 1) {
                $dbh->insertAdmin($_POST["email"], $hashedPassword);
            } else {
                $dbh->insertClient($_POST["email"], $hashedPassword);
            }
            $templateParams["error"] = "Registration succesfull";
            echo "Registration succesfull";
            header("Location: ?page=login");
        }
    }
} else {
    header("Location: ?page=account");
}

?>