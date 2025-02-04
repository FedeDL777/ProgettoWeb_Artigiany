<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$email = getloggeduseremail();

try {
    $orders = $dbh->getOrdersByEmail($email);
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
    $orders = [];
}
include("../includes/header.php");
?>

<main>
<div class="container-page">
<div class="row">
    <div class="container py-5">
        <h1 class="mb-4">I miei Pagamenti</h1>

        <?php if (empty($orders)): ?>
            <div class="alert alert-info text-center py-5">
                <p class="mb-0">Non è stato effettuato nessun ordine</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($orders as $order): ?>
                    <div class="col-12 col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Ordine: <?= htmlspecialchars($order['Data_']) ?></h5>
                                <p class="card-text">
                                    Luogo di consegna: <strong><?= htmlspecialchars($order['Luogo']) ?></strong>
                                </p>

                                <div class="carousel slide mb-3" id="carousel-<?= $order['ID'] ?>" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php $products = $dbh->searchCartProducts($order['ID']); 
                                        ?>
                                            <?php foreach ($products as $product): ?>
                                                <div class="carousel-item active">
                                                    <img src="<?= htmlspecialchars($product['PathImmagine']) ?>" 
                                                        class="d-block w-100 rounded" 
                                                        alt="<?= htmlspecialchars($product['product_name']) ?>">
                                                    <div class="carousel-caption bg-dark bg-opacity-75 p-2 rounded">
                                                        <h5 class="text-light"><?= htmlspecialchars($product['product_name']) ?></h5>
                                                        <p class="text-light mb-1">
                                                            <strong>Descrizione:</strong> <?= htmlspecialchars($product['description']) ?>
                                                        </p>
                                                        <p class="text-light mb-1">
                                                            <strong>Prezzo:</strong> €<?= number_format($product['price'], 2) ?>
                                                        </p>
                                                        <p class="text-light mb-0">
                                                            <strong>Quantità:</strong> <?= htmlspecialchars($product['quantity']) ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?= $order['ID'] ?>" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?= $order['ID'] ?>" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>

                                <p class="card-text">
                                    Prezzo totale: <strong class="text-success">€ <?= number_format($order['Totale'], 2) ?></strong>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
include("../includes/footer.php");
?>