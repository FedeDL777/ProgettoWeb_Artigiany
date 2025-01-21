<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

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

include("../includes/header.php");
?>
<main>
    
    <div class="d-flex justify-content-center align-items-center vh-90">
        <div class="container-fluid text-center px-3">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                    <form action="#" method="POST" class="p-4 shadow-sm rounded bg-white">
                        <h2 class="mb-4">Login</h2>
                        <?php if (isset($login_error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $login_error; ?>
                            </div>
                        <?php endif; ?>
                        <div class="mb-3 text-start">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3 text-start">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100">Invia</button>
                    </form>
                    <div class="mt-3">
                        Non hai un account? <a href="register.php">Registrati</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
    include("../includes/footer.php");
?>