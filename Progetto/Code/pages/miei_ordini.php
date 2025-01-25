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
    $carrello = $dbh->searchClientCart($email);
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
    $cards = [];
    $carrello = [];
}
/*
<div class="col-md-5">
<img src="<?= htmlspecialchars($product['PathImmagine']) ?>" 
     alt="<?= htmlspecialchars($product['Nome']) ?>" 
     class="img-fluid rounded shadow-sm">
</div>

<!-- Sezione dettagli prodotto -->
<div class="col-md-7">
<h1 class="mb-4"><?= htmlspecialchars($product['Nome']) ?></h1>
<p class="text-muted">Codice prodotto: <?= htmlspecialchars($product['productID']) ?></p>
<p class="mt-4"><?= nl2br(htmlspecialchars($product['Descrizione'])) ?></p>
<h3 class="mt-4 text-success">Prezzo: € <?= number_format($product['Costo'], 2) ?></h3>*/
include("../includes/header.php");
?>

<main>
    <link rel="stylesheet" href="../CSS/styles.css">
    <div class="container py-5">
        <h1 class="mb-4">I tuoi Ordini</h1>
        
        <?php if (empty($cards)): ?>
                <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span>Nessuna carta registrata</span>
            <a href="add_card.php" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Aggiungi carta
            </a>
        <?php endif; ?>
         
            <?php if (empty($carrello)): ?>
                <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span>Il tuo carrello è vuoto o inesistente</span>
            <a href="home.php" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> To
            </a>
    
        <?php else: ?>


            
        
            <div class="row">
            <!-- Sezione immagine prodotto -->
            <div class="col-md-5">
                <img src="<?= htmlspecialchars($product['PathImmagine']) ?>" 
                     alt="<?= htmlspecialchars($product['Nome']) ?>" 
                     class="img-fluid rounded shadow-sm">
            </div>
            
            <!-- Sezione dettagli prodotto -->
            <div class="col-md-7">
                <h1 class="mb-4"><?= htmlspecialchars($product['Nome']) ?></h1>
                <p class="text-muted">Codice prodotto: <?= htmlspecialchars($product['productID']) ?></p>
                <p class="mt-4"><?= nl2br(htmlspecialchars($product['Descrizione'])) ?></p>
                <h3 class="mt-4 text-success">Prezzo: € <?= number_format($product['Costo'], 2) ?></h3>
            
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php
include("../includes/footer.php");

// Funzione per mascherare il numero della carta
function maskCardNumber($number) {
    return '**** **** **** ' . substr(strval($number), -4);
}
?>