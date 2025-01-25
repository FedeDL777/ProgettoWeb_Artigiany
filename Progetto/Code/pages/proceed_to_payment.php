<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica se l'utente è loggato
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Recupera l'email dell'utente loggato
$email = getLoggedUserEmail();

// Verifica se esiste un luogo associato all'email
$userAddress = $dbh->getUserAddress($email);
try {
    $carrello = $dbh->searchClientCart($email);
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
    $carrello = [];
}
$idCarrello = $carrello[0]['cartID'];
$prodottiCarrello = $dbh->searchCartProducts($idCarrello);
$totale = 0;
foreach ($prodottiCarrello as $item){
    $totale += $item['price'] * $item['quantity']; 
}


$dbh->insertOrder($idCarrello, $userAddress, $_POST['selected_card'],  $email, $totale);
$dbh->useCart($idCarrello);
$dbh->insertNotification($email, "Il tuo ordine, con la carta" . htmlspecialchars(maskCardNumber($_POST['selected_card'])) . " per un totale di " . $totale .  " è stato effettuato con successo!", 
    $dbh->getOrderByCardandEmail($email, $_POST['selected_card'])[0]['ID']);
header("Location: accountClient.php");
?>