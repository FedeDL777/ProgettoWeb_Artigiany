<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$email = getloggeduseremail();


try {
    $cards = $dbh->getUserCards($email);
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
    $cards = [];
}

include("../includes/header.php");
?>

<main>
    <div class="container-page">
        <div class="row">
   
            <div class="container py-5">
                <h1 class="mb-4">Le tue carte di pagamento</h1>
                <!-- 

                -->
                        <div class="d-flex justify-content-center mt-4">
                            <a href="add_card.php" class="btn btn-success btn-lg">
                                <i class="bi bi-plus-circle"></i> Aggiungi una carta
                            </a>
                        </div>
            </div>


        </div>
    </div>
</main>

<?php
include("../includes/footer.php");

// Funzione per mascherare il numero della carta

?>