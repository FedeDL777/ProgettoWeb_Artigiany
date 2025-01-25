<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica se l'utente è loggato
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}
include("../includes/header.php");

// Recupera l'email dell'utente loggato
$email = getLoggedUserEmail();


// Recupera il cartID dell'utente loggato
$cart = $dbh->searchClientCart($email);

// Se il carrello non esiste

if (!$cart) {
    echo "<div class='container py-5'><div class='alert alert-info'>Il tuo carrello non esiste!</div></div>";
    exit();
}

$cart = $cart[0];
$cart_id = $cart['cartID'];

// Recupera gli articoli del carrello
$cart_items = $dbh->searchCartProducts($cart_id);

?>

<main>
    <link rel="stylesheet" href="../CSS/styles.css">
    <div class="container-page">
        <div class="row">
    <div class="container py-5">
        <h1 class="mb-4 text-center">Il tuo carrello</h1>

        <?php if (empty($cart_items)): ?>

            <div class="alert alert-info text-center">Il tuo carrello è vuoto!</div>
        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Prodotto</th>
                            <th>Prezzo</th>
                            <th>Quantità</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                        <td class="text-center">
                                    <?php echo htmlspecialchars($item['product_name']); ?>
                                </td>
                                <td class="text-center">
                                    <?php echo number_format($item['price'], 2); ?> €
                                </td>
                                <td class="text-center">
                                    <?php echo number_format($item['quantity'], 0); ?>
                                </td>
                            <td class="text-center">
                                <form method="POST" action="add_to_cart.php" style="display: inline-block;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-sm btn-primary">Aumenta</button>
                                </form>
                                <form method="POST" action="add_to_cart.php" style="display: inline-block;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                    <input type="hidden" name="quantity" value="-1">
                                    <button type="submit" class="btn btn-sm btn-danger">Riduci</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
    <a href="home.php" class="btn btn-outline-secondary">Continua lo shopping</a>
    <form method="POST" action="mio_ordine.php" style="display: inline;">
        <button type="submit" class="btn btn-success">Procedi al pagamento</button>
    </form>
</div>

        <?php endif; ?>
    </div>
</div>
</div>
</main>

<?php
include("../includes/footer.php");
?>