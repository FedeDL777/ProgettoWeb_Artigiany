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
echo $email;

// Recupera il cartID dell'utente loggato


// Se il carrello non esiste o manca la chiave 'Used'
if (!$dbh->searchClientCart($email) || !isset($cart['Used']) || $cart['Used'] == 1) {
    $dbh->insertCart($email);
    // Dopo l'inserimento, ricarica il carrello aggiornato
    $cart = $dbh->searchClientCart($email);
}
else {
    $cart = $dbh->searchClientCart($email);
}
$cart = $cart[0];
$quantity = (int)$_POST["quantity"]; // Converte in intero
$productId = $_POST["product_id"] ?? null;

if (!empty($dbh->getProductInCart($cart['cartID'], $productId))) {
    $dbh->AddCartProductQuantity($cart['cartID'], $productId, $quantity);
} 
else{
        $dbh->insertProductInCart($cart['cartID'], $productId, $quantity);
}

 header("Location: cart.php");

?>