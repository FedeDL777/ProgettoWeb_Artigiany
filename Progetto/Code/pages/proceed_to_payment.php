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
if (!$userAddress) {
    // Se non esiste un luogo di consegna, reindirizza alla pagina delle impostazioni
    header("Location: impostazioni_consegna.php");
    exit();
}

try {
    $carrello = $dbh->searchClientCart($email);
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
    $carrello = [];
}
$prodottiCarrello = $dbh->searchCartProducts($carrello[0]['cartID']);
$totale = 0;
foreach ($prodottiCarrello as $item){
    $totale += $item['price'] * $item['quantity']; 
}
$idCarrello = $carrello[0]['cartID'];
echo "Carello " . $idCarrello;
echo " UserAddress " . $userAddress['address'];
echo " NumeroCarta " . $_POST['Numero'];
echo " Email " . $email;
echo " Totale " . $totale;

$dbh->insertOrder($idCarrello, $userAddress['address'], $_POST['Numero'],  $email, $totale);
$dbh->useCart($idCarrello);
$dbh->insertNotification($email, "Il tuo ordine, con la carta" . htmlspecialchars(maskCardNumber($_POST['Numero'])) . " per un totale di " . $totale .  " è stato effettuato con successo!");
header("Location: accountClient.php");
?>