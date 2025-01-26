<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$email = getLoggedUserEmail();

// Recupero il carrello dell'utente
$cart = $dbh->searchClientCart($email);

$cart_id = $cart[0]['cartID'];

// Recupero e valido i dati inviati dal form
$material = trim($_POST['material'] ?? '');
$description = trim($_POST['description'] ?? '');
$selectedCells = json_decode($_POST['selectedCells'] ?? '[]', true);
$totalPrice = floatval($_POST['totalPrice'] ?? 0);

if (empty($material) || empty($description) || empty($selectedCells) || $totalPrice <= 0) {
    die("Errore: dati non validi. Verifica di aver compilato tutti i campi richiesti.");
}

// Nome del prodotto personalizzato
$productName = "Custom Product";

// Avvio una transazione per gestire eventuali errori
$dbh->beginTransaction();

try {
    // Aggiungi il prodotto personalizzato
    $result = $dbh->insertProduct($productName, $description, $totalPrice, "C:\xampp1\htdocs\dashboard\ProgettoWeb_Artigiany\Progetto\Code\pages\images\CUSTOMPRODUCT.png", 1, $email);

    if (!$result) {
        throw new Exception("Errore durante la creazione del prodotto personalizzato.");
    }

    // Recupero l'ID del prodotto appena creato
    $lastProductId = $dbh->getLastProductID()[0]['productID'] ?? null;
    if (!$lastProductId) {
        throw new Exception("Errore nel recupero dell'ID del prodotto appena creato.");
    }

    // Aggiungi il prodotto al carrello
    $quantity = 1; // Quantità predefinita
    $insertResult = $dbh->insertProductInCart($cart_id, $lastProductId, $quantity);

    if (!$insertResult) {
        throw new Exception("Errore durante l'inserimento del prodotto nel carrello.");
    }

    // Associa i materiali selezionati al prodotto
    if (!empty($selectedCells)) {
        $firstMaterial = $selectedCells[0]; // Prendi solo il primo elemento dell'array
        // Inserisci il materiale nel database
        $dbh->insertProductMaterial($lastProductId, $firstMaterial); 
    } else {
        die("Errore: nessun materiale selezionato.");
    }

    // Conferma la transazione
    $dbh->commit();
    header("Location: cart.php");
    exit();

} catch (Exception $e) {
    // Rollback in caso di errore
    $dbh->rollback();
    die("Errore: " . $e->getMessage());
}
?>
