<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Controlla se esiste una query di ricerca
$searchQuery = isset($_GET['searched-product']) ? trim($_GET['searched-product']) : '';

$products = [];
if ($searchQuery !== '') {
    // Usa il metodo della classe DatabaseHelper per cercare i prodotti
    $products = $dbh->searchProducts($searchQuery);
}

include("../includes/header.php");
?>

<main class="container my-4">
    <link rel="stylesheet" href="../CSS/styles.css">

    <body>
        <div class="container-page">
            <div id="main-content">
                <h1 class="text-center mb-4">Risultati per "<?= htmlspecialchars($searchQuery) ?>"</h1>

                <?php if ($searchQuery === ''): ?>
                <p class="text-center">Inserisci un termine di ricerca per trovare articoli.</p>
                <?php elseif (empty($products)): ?>
                <p class="text-center">Nessun prodotto trovato per la tua ricerca.</p>
                <?php else: ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <a href="product.php?product_id=<?= urlencode($product['productID']) ?>">
                                <img src="<?= htmlspecialchars($product['PathImmagine']) ?>" class="card-img-top"
                                    alt="<?= htmlspecialchars($product['Nome']) ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="product.php?product_id=<?= urlencode($product['productID']) ?>"
                                        class="text-dark">
                                        <?= htmlspecialchars($product['Nome']) ?>
                                    </a>
                                </h5>
                                <p class="card-text"><?= htmlspecialchars($product['Descrizione']) ?></p>
                                <p class="card-text"><strong>€ <?= number_format($product['Costo'], 2) ?></strong></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
</main>

<?php include("../includes/footer.php"); ?>
