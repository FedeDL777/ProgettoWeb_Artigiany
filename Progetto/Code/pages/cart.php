<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}
include("../includes/header.php");

$email = getLoggedUserEmail();
$cart = $dbh->searchClientCart($email);

if (!$cart) {
    echo "<div class='container py-5'><div class='container-page'>
        <div id='main-content'><div class='alert alert-info'>Il tuo carrello non esiste!</div></div></div></div>";
    include("../includes/footer.php");
    exit();
}

$cart = $cart[0];
$cart_id = $cart['cartID'];
$cart_items = $dbh->searchCartProducts($cart_id);
?>

<main>
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
                                    <th>Descrizione</th>
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
                                        <!-- Aggiungi il link per visualizzare la griglia se il prodotto è personalizzato -->
                                        <?php if ($item['is_custom'] == 1): ?>
                                            <br>
                                            <a href="custom_product_grid.php?productID=<?php echo $item['product_id']; ?>" class="btn btn-sm btn-info mt-2">
                                                Visualizza Griglia
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center description-cell">
                                        <?php echo nl2br(htmlspecialchars($item['description'])); ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo number_format($item['price'], 2); ?> €
                                    </td>
                                    <td class="text-center">
                                        <?php echo number_format($item['quantity'], 0); ?>
                                    </td>
                                    <td class="text-center">
                                        <!-- Form per aumentare la quantità -->
                                        <form method="POST" action="add_to_cart.php" style="display: inline-block;">
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-primary">+</button>
                                        </form>
                                        <!-- Form per diminuire la quantità -->
                                        <form method="POST" action="add_to_cart.php" style="display: inline-block;">
                                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                            <input type="hidden" name="quantity" value="-1">
                                            <button type="submit" class="btn btn-sm btn-danger">-</button>
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