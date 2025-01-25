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

include("../includes/header.php");
?>

<main>
    <link rel="stylesheet" href="../CSS/styles.css">
    <div class="container py-5">
        <h1 class="mb-4 text-center">I tuoi Ordini</h1>

        <!-- Verifica se non ci sono carte registrate -->
        <?php if (empty($cards)): ?>
            <div class="alert alert-warning d-flex justify-content-between align-items-center">
                <span>Non hai registrato alcuna carta!</span>
                <a href="add_card.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i> Aggiungi una carta
                </a>
            </div>
        <?php endif; ?>

        <!-- Verifica se il carrello è vuoto -->
        <?php if (empty($carrello)): ?>
            <div class="alert alert-info d-flex justify-content-between align-items-center">
                <span>Il tuo carrello è vuoto o inesistente.</span>
                <a href="home.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-shop"></i> Vai ai prodotti
                </a>
            </div>
        <?php else: ?>
            <!-- Mostra i prodotti nel carrello -->
            <div class="row">
                <h2 class="mb-4">Anteprima ordine</h2>
                <?php
                $prodottiCarrello = $dbh->searchCartProducts($carrello[0]['cartID']);
                $totale = 0;
                ?>

                <!-- Ciclo per mostrare i prodotti -->
                <?php foreach ($prodottiCarrello as $item): ?>
                    <?php $totale += $item['price'] * $item['quantity']; ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <!-- Immagine del prodotto -->
                            <img src="<?= htmlspecialchars($item['PathImmagine']) ?>" 
                                class="card-img-top img-fluid" 
                                alt="<?= htmlspecialchars($item['product_name']) ?>">
                            
                            <div class="card-body">
                                <!-- Nome del prodotto -->
                                <h5 class="card-title"><?= htmlspecialchars($item['product_name']) ?></h5>
                                
                                <!-- Dettagli del prodotto -->
                                <p class="card-text">
                                    Prezzo: <span class="text-success">€ <?= number_format($item['price'], 2) ?></span><br>
                                    Quantità: <strong><?= htmlspecialchars($item['quantity']) ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Totale ordine -->
                <div class="col-12">
                    <div class="alert alert-success text-center">
                        <h4>Totale ordine: € <?= number_format($totale, 2) ?></h4>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
include("../includes/footer.php");
?>