<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$email = getLoggedUserEmail();

if (isset($_POST['numero'])) {
    $numero = $_POST['numero'];
    
    try {
        $success = $dbh->deleteCreditCard($email, $numero);
        if ($success) {
            $_SESSION['success_message'] = "Carta eliminata con successo";
        } else {
            $_SESSION['error_message'] = "Errore nell'eliminazione della carta";
        }
    } catch (Exception $e) {
        error_log("Errore database: " . $e->getMessage());
        $_SESSION['error_message'] = "Errore tecnico durante l'eliminazione";
    }
}

header("Location: info_carte.php");
exit();
?>