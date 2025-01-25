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
if (!$dbh->searchClientCart($email)) {
    $dbh->insertCart($email);
    echo "Carrello inserito";
    // Dopo l'inserimento, ricarica il carrello aggiornato
    $cart = $dbh->searchClientCart($email);
}
else {
    $cart = $dbh->searchClientCart($email);
}
$cart = $cart[0];
$quantity = (int)$_POST["quantity"]; // Converte in intero
$productId = $_POST["product_id"] ?? null;
echo $productId . 'Prodotto';
echo $cart['cartID'] . 'carrello';
$res = $dbh->getProductInCart($cart['cartID'], $productId);
if (!empty($res) && $quantity > 0) {
    $dbh->AddCartProductQuantity($cart['cartID'], $productId, $quantity);
    echo "Aggiunta quantità";
} 
else if ($quantity > 0) {
        $dbh->insertProductInCart($cart['cartID'], $productId, $quantity);
        echo "Aggiunto prodotto";
}
else if (!empty($dbh->getProductInCart($cart['cartID'], $productId))) {
    $dbh->decreaseCartProductQuantity($cart['cartID'], $productId, $quantity);
    echo "Diminuita quantità";
}

 header("Location: cart.php");

?>