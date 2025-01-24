<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica se l'utente è loggato
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}
// Recupera l'email dell'utente loggato
 // Ottiene i dati dell'utente loggato
$email = getLoggedUserEmail();

// Recupera il cartID dell'utente loggato
$cart = $dbh->searchClientCart($email);
// Se il carrello non esiste
if (!$cart || $cart['used'] == 1) {
    $dbh->insertCart($email);
}
foreach ($POST["Quantity"] as $q ) {
    $dbh->insertProductInCart($cart['cartID'], $POST["product_id"]);
}
header("Location: cart.php");
?>