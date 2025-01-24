<?php
include_once("../includes/bootstrap.php");
require_once("../includes/functions.php");
include("../includes/header.php");

// Ottieni l'ID della categoria dall'URL
$categoryID = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;

// Recupera i dettagli della categoria
$category = $dbh->getCategoryById($categoryID);
if (!$category) {
    header("Location: error.php?message=Categoria+non+trovata");
    exit();
}

// Recupera i prodotti della categoria
$products = $dbh->getProductsByCategory($categoryID);
?>

<link rel="stylesheet" href="../CSS/styles.css">

<main class="container my-4">
    <div class="container-page">
        <div id="main-content">
            <h1 class="text-center mb-4"><?php echo htmlspecialchars($category['Nome']); ?></h1>

            <?php if (empty($products)): ?>
                <p class="text-center">Nessun prodotto disponibile in questa categoria</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <a href="product.php?productId=<?= urlencode($product['productID']) ?>">
                                    <img src="<?php echo htmlspecialchars($product['PathImmagine']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($product['Nome']); ?>"
                                         style="height: 200px; object-fit: cover;">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="product.php?productId=<?= urlencode($product['productID']) ?>" 
                                           class="text-dark">
                                            <?php echo htmlspecialchars($product['Nome']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text"><?php echo htmlspecialchars($product['Descrizione']); ?></p>
                                    <p class="card-text"><strong>€<?php echo number_format($product['Costo'], 2); ?></strong></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include("../includes/footer.php"); ?>