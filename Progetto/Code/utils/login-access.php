<?php
require_once("../includes/bootstrap.php");

if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    //template
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        //c'è più di uno user con la stessa email
        //controllo admin
        $adminResult = $dbh->getHashedPasswordAdmin($_POST["email"]);
        if (!empty($adminResult) && isset($adminResult[0]["password_"])) {
            $hashedPasswordAdmin = $adminResult[0]["password_"];
            $loginResultAdmin = password_verify($_POST["password"], $hashedPasswordAdmin);
            if ($loginResultAdmin) {
                // Admin login
                //Apre la sessione ad admin
                registerAdminLogged($_POST);
                //da cambiare alla pagina account
                header("Location: ../pages/home.php");
                exit();
            }

            //non è admin
            $hashedPasswordClient = $clientResult[0]["password_"];
            $loginResultClient = password_verify($_POST["password"], $hashedPasswordClient);
            if ($loginResultClient) {
                // Admin login
                //Apre la sessione a client
                registerLoggedUser($_POST);
                //da cambiare
                header("Location: ../pages/home.php");
                exit();
            }
             else {
            //$templateParams["error"] = "Password o UsernameErrati!";
            echo "Password o UsernameErrati!";
            }
        } else {
        //$templateParams["error"] = "Password o UsernameErrati!";
        echo "Password o UsernameErrati!";
         }
     
    }   
    else {
        //da cambiare alla pagina account
        header("Location: ../pages/home.php");
    }
}

?>

