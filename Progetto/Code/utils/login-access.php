<?php
require_once("../includes/bootstrap.php");
var_dump("entrato");
if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    var_dump("non loggato");
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        var_dump("parametri scritti bene");
        //c'è più di uno user con la stessa email
        if (count($dbh->checkUsermail($_POST["email"]))) {
            var_dump("già presente");
            // Registration failed
            $templateParams["error"] = "Un account con questa email è già stato registrato";
            echo "Login effettuato";
            header("Location: ../pages/register.php");
        } 
        else {
            $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

                $dbh->insertClient($_POST["email"], $hashedPassword);
                
            $templateParams["error"] = "Registration succesfull";
            var_dump("registrato");
            echo "Registration succesfull";
            header("Location: ../pages/login.php");
        }
    }
} else {

    header("Location: ../pages/home.php");
}

?>