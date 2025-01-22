<?php
ob_start();

include_once("../includes/bootstrap.php");
require_once("../includes/functions.php");

if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        //controllo admin
        $adminResult = $dbh->getHashedPasswordAdmin($_POST["email"]);
        $loginAdmin = password_verify($_POST["password"], $adminResult[0]["Pw"]);
        if($loginAdmin && $dbh->checkLogin($_POST["email"], $adminResult[0]["Pw"], 1)){
            registerAdminLogged($_POST);
            unset($login_error);
            header("Location: accountAdmin.php");
            exit();
        }
        //controllo client 
        $clientResult = $dbh->getHashedPasswordClient($_POST["email"]);
        $loginClient = password_verify($_POST["password"], $clientResult[0]["Pw"]);
        if($loginClient && $dbh->checkLogin($_POST["email"], $clientResult[0]["Pw"], 0)){
            registerLoggedUser($_POST);
            unset($login_error);
            header("Location: accountClient.php");
            exit();
        }
        else {
            $login_error = "Errore! Controllare email o password!";
        }
        
    }
    else {
        $login_error = "Errore! Controllare email o password!";
    }
}
else {
    // Se già loggato, reindirizza alla pagina corretta
    if (isAdminLoggedIn()) {
        header("Location: accountAdmin.php");
    
    } else {
        header("Location: accountClient.php");
    }
    exit();
}


/*
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $login_result = $db->checkLogin($_POST["email"], $_POST["password"]);
    if (count($login_result) == 0) {
        $login_error = "Errore! Controllare email o password!";
    } else {
        unset($login_error);
        registerLoggedUser($login_result[0]);
    }
}

if (isUserLoggedIn()) {
    header("Location: /pages/profile.php");
}

// da cambiare
if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    // Se non è loggato né come utente né come admin
    if (isset($_POST["email"]) && isset($_POST["pw"])) {
        // Verifica admin
        $adminResult = $dbh->getHashedPasswordAdmin($_POST["email"]);
        if (!empty($adminResult) && isset($adminResult[0]["pw"])) {
            $hashedPasswordAdmin = $adminResult[0]["Pw"];
            //$dbh->checkLogin($_POST["email"], password_verify($_POST["pw"], $hashedPasswordAdmin), 1) 
            if (password_verify($_POST["pw"], $hashedPasswordAdmin)) {
                registerAdminLogged($_POST); // Registra l'admin nella sessione
                header("Location: ../pages/accountAdmin.php");
                exit();
            }
        }

        // Verifica client
        $clientResult = $dbh->getHashedPasswordClient($_POST["email"]);
        if (!empty($clientResult) && isset($clientResult[0]["pw"])) {
            $hashedPasswordClient = $clientResult[0]["pw"];
            if (password_verify($_POST["pw"], $hashedPasswordClient))   {
                registerLoggedUser($user); // Registra l'utente nella sessione
                header("Location: ../pages/accountClient.php");
                exit();
            }
        }

        // Se nessuna verifica ha successo, mostra errore
        $login_error = "Error! Check username or password!";
    }
} else {
    // Se già loggato, reindirizza alla pagina corretta
    if (isAdminLoggedIn()) {
        header("Location: ../pages/accountAdmin.php");
    
    } else {
        header("Location: ../pages/accountClient.php");
    }
    exit();
}*/

include("../includes/header.php");
?>

<main class="container-fluid py-4">

    <body>
        <link rel="stylesheet" href="../CSS/styles.css">
        <div class="d-flex justify-content-center align-items-center vh-90">
            <div class="container-fluid text-center px-3">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                        <form action="#" method="POST" class="p-4 shadow-sm rounded bg-white">
                            <h2 class="mb-4">Login</h2>
                            <div class="mb-3 text-start">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3 text-start">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" id="password" name="password" class="form-control" required>
                            </div>
                            <?php if (isset($login_error)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $login_error; ?>
                                </div>
                            <?php endif; ?>
                            <button type="submit" name="submit" class="btn btn-primary w-100">Invia</button>
                        </form>
                        <div class="mt-3">
                            Non hai un account? <a href="register.php">Registrati</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</main>

<?php
include("../includes/footer.php");
ob_end_flush();
?>
