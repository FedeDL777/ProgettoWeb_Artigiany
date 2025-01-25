<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    header("Location: home.php");
}
logout();
include("../includes/header.php");
?>

<main>
    <div class="container-page">
        <div class="row">
            <div class="d-flex justify-content-center align-items-center">
                <div class="container text-center">
                    <h1>Logout effettuato</h1>
                    <!-- Bottone per tornare alla home -->
                    <a href="home.php" class="btn btn-primary btn-lg mt-4">Torna alla Home</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include("../includes/footer.php");
if (session_status() === PHP_SESSION_ACTIVE)session_destroy();

?>
