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
            //$templateParams["error"] = "Error! Check username or password!";
            echo "Error! Check username or password!";
            }
        } else {
        //$templateParams["error"] = "Error! Check username or password!";
        echo "Error! Check username or password!";
         }
     
    }   
    else {
        //da cambiare alla pagina account
        header("Location: ../pages/home.php");
    }
}
/*
case "login":
        if (!isUserLoggedIn() && !isAdminLoggedIn()) {
            $templateParams["title"] = "E-lixirium - Login";
            $templateParams["content"] = "login-form.php";
            if (isset($_POST["username"]) && isset($_POST["password"])) {
                // get hashed admin password 
                $adminResult = $dbh->getHashedPasswordAdmin($_POST["username"]);
                if (!empty($adminResult) && isset($adminResult[0]["password"])) {
                    $hashedPassword = $adminResult[0]["password"];
                    $loginResult = password_verify($_POST["password"], $hashedPassword);
                    if ($loginResult) {
                        // Admin login
                        registerAdminLogged($_POST);
                        header("Location: ?page=account");
                        exit();
                    }
                }

                // try with the user
                $userResult = $dbh->getHashedPasswordUser($_POST["username"]);
                if (!empty($userResult) && isset($userResult[0]["password"])) {
                    $hashedPassword = $userResult[0]["password"];
                    $loginResult = password_verify($_POST["password"], $hashedPassword);
                    if ($loginResult) {
                        // User login
                        $user = $dbh->getUserInfo($_POST["username"])[0];
                        registerLoggedUser($user);
                        header("Location: ?page=account");
                        exit();
                    } else {
                        $templateParams["error"] = "Error! Check username or password!";
                    }
                } else {
                    $templateParams["error"] = "Error! Check username or password!";
                }
            }
        } else {
            header("Location: ?page=account");
        }*/
?>

