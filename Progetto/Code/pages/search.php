<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Controlla se esiste una query di ricerca
$searchQuery = isset($_GET['searched-product']) ? trim($_GET['searched-product']) : '';

$products = [];
if ($searchQuery !== '') {
    // Connessione al database e ricerca dei prodotti
    $stmt = $db->prepare("SELECT ID, Nome, Descrizione, Costo, PathImmagine FROM PRODOTTO WHERE Nome LIKE ? OR Descrizione LIKE ?");
    $likeQuery = "%" . $searchQuery . "%";
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
}

include("../includes/header.php");
?>

<main class="container my-4">
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
        <a href="product.php?product_id=<?= urlencode($product['ID']) ?>">
            <img src="<?= htmlspecialchars($product['PathImmagine']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['Nome']) ?>">
        </a>
        <div class="card-body">
            <h5 class="card-title">
                <a href="product.php?product_id=<?= urlencode($product['ID']) ?>" class="text-dark">
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
</main>

<?php include("../includes/footer.php"); ?>