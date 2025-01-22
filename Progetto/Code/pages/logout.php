<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");
if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    header("Location: home.php");
}
logout();
include("../includes/header.php");
?>
<body>
    <main>
        <div class="d-flex justify-content-center align-items-center">
            <div class="container text-center">
                <h1>Logout effettuato</h1>
            </div>
        </div>
    </main>
</body>

<?php
include("../includes/footer.php");
session_destroy();
?>