<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Validazione dell'ID prodotto dalla query string
if (!isset($_GET['productId']) || !is_numeric($_GET['productId'])) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Prodotto non valido!</div></div>";
    exit();
}

$productId = intval($_GET['productId']);

// Recupera i dettagli del prodotto dal database
$product = $dbh->getProductById($productId);
if (!$product) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Prodotto non trovato!</div></div>";
    exit();
}

include("../includes/header.php");
?>
<main>
    <div class="container-page">
        <div id="main-content">
    <div class="container py-5">
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

                <!-- Pulsanti di azione -->
                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= $productId ?>">
                    <div class="form-group mt-4">
                        <label for="quantity">Quantità:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control w-25" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Aggiungi al carrello</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</main>
<?php
include("../includes/footer.php");
?>
