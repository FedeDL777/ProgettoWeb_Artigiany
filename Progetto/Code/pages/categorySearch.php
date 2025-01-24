<?php
include_once("../includes/bootstrap.php");
require_once("../includes/functions.php");

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

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['Nome']); ?> - Artigiany</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/styles.css">
</head>
<body>

<div class="container-page">
<div id="main-content">
    <?php include("../includes/header.php"); ?>
    
    <main class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <?php include("../includes/sidebar.php"); ?>
            
            <!-- Contenuto principale -->
            <div class="col-md-10 col-sm-12">
                <h2 class="mb-4"><?php echo htmlspecialchars($category['Nome']); ?></h2>
                
                <?php if (empty($products)): ?>
                    <div class="alert alert-info">Nessun prodotto disponibile in questa categoria</div>
                <?php else: ?>
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php foreach ($products as $product): ?>
                            <div class="col">
                                <div class="card h-100">
                                    <img src="<?php echo htmlspecialchars($product['PathImmagine']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($product['Nome']); ?>"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product['Nome']); ?></h5>
                                        <p class="card-text"><?php echo htmlspecialchars($product['Descrizione']); ?></p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="h5">€<?php echo number_format($product['Costo'], 2); ?></span>
                                            <button class="btn btn-primary">Aggiungi al carrello</button>
                                        </div>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</div>
</div>
</body>
</html>