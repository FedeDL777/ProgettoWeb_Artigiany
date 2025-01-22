<?php

include_once("../includes/bootstrap.php");
require_once("../includes/functions.php");

/*
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

*/
if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    //template
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        //c'è più di uno user con la stessa email
        //controllo admin
        $adminResult = $dbh->getHashedPasswordAdmin($_POST["email"]);
        if (!empty($adminResult) && isset($adminResult[0]["password"])) {
            $hashedPasswordAdmin = $adminResult[0]["password"];
            $loginResultAdmin = password_verify($_POST["password"], $hashedPasswordAdmin);
            if ($loginResultAdmin) {
                // Admin login
                //Apre la sessione ad admin
                registerAdminLogged($_POST);
                unset($login_error);  
                header("Location: ../pages/accountAdmin.php");
                exit();
            }

            //non è admin
            $hashedPasswordClient = $clientResult[0]["password"];
            $loginResultClient = password_verify($_POST["password"], $hashedPasswordClient);
            if ($loginResultClient) {
                // Admin login
                //Apre la sessione a client
                registerLoggedUser($_POST);
                unset($login_error);  
                header("Location: ../pages/accountClient.php");
                exit();
            }
             else {
                $login_error = "Error! Check username or password!";
            }
        } else {
            $login_error = "Error! Check username or password!";
         }
     
    }   

}
else {
    if(isAdminLoggedIn()){
        header("Location: ../pages/accountAdmin.php");
    }
    else{
        header("Location: ../pages/accountClient.php");
    }
}

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
?>