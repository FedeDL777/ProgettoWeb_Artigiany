<?php

include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica se l'utente è loggato
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Recupera l'email dell'utente loggato
$user = getLoggedUser(); // Ottiene i dati dell'utente loggato
$email = $user['Email'];

// Recupera il cartID dell'utente loggato
$cart = $dbh->searchClientCart($email);
// Se il carrello non esiste
if (!$cart) {
    echo "<div class='container py-5'><div class='alert alert-info'>Il tuo carrello è vuoto!</div></div>";
    exit();
}

$cart_id = $cart['cartID'];

// Recupera gli articoli del carrello
$cart_items = $dbh->searchCartProducts($cart_id);

include("../includes/header.php");
?>
<main>
<link rel="stylesheet" href="../CSS/styles.css">
    <div class="container py-5">
        <h1 class="mb-4">Il tuo carrello</h1>
        <?php if (empty($cart_items)): ?>
            <div class="alert alert-info">Il tuo carrello è vuoto!</div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Prodotto</th>
                        <th>Prezzo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo number_format($item['price'], 2); ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>
<?php
include("../includes/footer.php");
?>