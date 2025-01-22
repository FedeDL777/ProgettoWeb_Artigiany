<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica se l'utente è loggato
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Recupera l'ID del prodotto selezionato dalla query string
if (!isset($_GET['product_id']) || empty($_GET['product_id'])) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Prodotto non trovato!</div></div>";
    exit();
}

$product_id = intval($_GET['product_id']);

// Recupera i dettagli del prodotto dal database
$product = $dbh->getProductById($product_id);
if (!$product) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Prodotto non trovato!</div></div>";
    exit();
}

include("../includes/header.php");
?>
<main>
<link rel="stylesheet" href="../CSS/styles.css">
    <div class="container py-5">
        <div class="row">
            <!-- Sezione immagine prodotto -->
            <div class="col-md-5">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="img-fluid rounded shadow-sm">
            </div>
            
            <!-- Sezione dettagli prodotto -->
            <div class="col-md-7">
                <h1 class="mb-4"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <p class="text-muted">Codice prodotto: <?php echo htmlspecialchars($product['product_code']); ?></p>
                <p class="mt-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <h3 class="mt-4 text-success">Prezzo: <?php echo number_format($product['price'], 2); ?> €</h3>

                <!-- Pulsanti di azione -->
                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <div class="form-group mt-4">
                        <label for="quantity">Quantità:</label>
                        <input type="number" id="quantity" name="quantity" class="form-control w-25" value="1" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Aggiungi al carrello</button>
                </form>
            </div>
        </div>
    </div>
</main>
<?php
include("../includes/footer.php");
?>
