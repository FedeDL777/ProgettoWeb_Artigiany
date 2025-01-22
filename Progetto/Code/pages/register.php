<?php

require_once("../includes/bootstrap.php");

if (!isUserLoggedIn() && !isAdminLoggedIn()) {

    if (isset($_POST["email"]) && isset($_POST["password"])) {

        //c'è più di uno user con la stessa email
        if (count($dbh->checkUsermail($_POST["email"]))) {

            // Registration failed
            $register_error = "Un account con questa email è già stato registrato";
            header("Location: register.php");
        } 
        else {
            $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

                $dbh->insertClient($_POST["email"], $hashedPassword);
            unset($register_error);
            
            header("Location: ../pages/login.php");
        }
    }
} else {
    $register_error = "Effettuare il logout per registrare un nuovo account";
    header("Location: register.php");
}


include("../includes/header.php");
?>
<main>
    <link rel="stylesheet" href="../CSS/styles.css">
    <div class="d-flex justify-content-center align-items-center vh-90">
        <div class="container-fluid text-center px-3">
            <?php if (isUserLoggedIn()): ?>
            <div class="text-center">
                <h1 class="mb-4">Benvenuto, <?= $_SESSION["name"] ?>!</h1>
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
            <?php else: ?>
            <div class="row justify-content-center">
                <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                    <form action="../utils/registra-utente.php" method="POST" class="p-4 shadow-sm rounded bg-white">
                        <h2 class="mb-4">Registrazione</h2>
                        <?php endif; ?>
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <?php if (isset($register_error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $register_error; ?>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100">Invia</button>
                    </form>
                    <div class="mt-3">
                        Hai già un account? <a href="login.php">Accedi</a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
include("../includes/footer.php");
?>