<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");
require_once("../classes/Product.php");
require_once("../classes/ProductList.php");
require_once("../classes/CreditCard.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    die();
}

$cart = new ProductList($db->getCart($_SESSION["email"]));
if(count($cart->getProducts())==0) {
    echo "Il carrello è vuoto";
    die();
}
#da fare getPrice
#TO DO!!!!
$price = $cart->getPrice($db);
$credit_cards = $db->getCreditCards($_SESSION["email"]);

if (isset($_POST['credit_card'])) {
    $selected_card = $_POST['credit_card'];
    if ($selected_card === 'new') {
        $db->addCreditCard($_SESSION["email"], $_POST['cardNumber'], $_POST['cardName'], $_POST['cardSurname'], $_POST['cardExpiration'] . '-01');
    }
    $cart_products = $cart->getProducts();
    $db->addOrder($_SESSION["email"], $cart_products, $price);
    $db->addNotification($_SESSION["email"], "Nuovo ordine", "Hai effettuato un nuovo ordine di " . number_format($price, 2) . "€");
    foreach($cart_products as $product) {
        $productData = $db->getProduct($product['id_product'])[0];
        $db->addNotification($productData["email"], "Nuovo ordine", "Hai ricevuto un nuovo ordine di " . $product["quantity"] . " " . $productData["name"] . ". E' stato ordinato da " . $_SESSION["email"]);
    }
    $db->emptyCart($_SESSION["email"]);
    foreach ($cart_products as $product) {
        $productData = $db->getProduct($product['id_product'])[0];
        $prod = new Product(...$productData);
        $db->seller->updateProduct($prod->getId(), availability: $prod->getAvailability() - $product["quantity"]);
        handleProductAvailabilityUpd($db, $prod->getId());
    }
    header("Location: home.php");    
}

include '../includes/header.php';
?>
<main>
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col">
                <h1 class="text-primary">Prezzo totale: <?= number_format($price, 2) ?> €</h1>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <h2>Indirizzo di spedizione</h2>
                <p>via dell'università 50, Cesena, Italia</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <h2>Metodo di pagamento</h2>
                <p>Seleziona la carta di credito:</p>
                <form method="POST" action="#">
                    <?php foreach ($credit_cards as $cc) {
                        $credit_card = new CreditCard($cc["number"], $cc["name"], $cc["surname"], $cc["expiration"]);
                    ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="credit_card" value="<?= $credit_card->getNumber() ?>" id="credit-card-<?= $credit_card->getNumber() ?>" required>
                            <label class="form-check-label" for="credit-card-<?= $credit_card->getNumber() ?>">
                                <?= $credit_card->getNumber() ?>
                            </label>
                        </div>
                    <?php } ?>
                    <!-- Option to add new credit card -->
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="credit_card" value="new" id="new-card" required>
                        <label class="form-check-label" for="new-card">Inserisci un'altra carta</label>
                    </div>

                    <!-- Form for adding a new credit card -->
                    <div id="new-card-form" class="mt-4 d-none">
                        <div class="card p-3">
                            <div class="mb-3">
                                <label for="cardNumber" class="form-label">Numero Carta</label>
                                <input type="text" class="form-control border border-2" id="cardNumber" name="cardNumber">
                            </div>
                            <div class="mb-3">
                                <label for="cardName" class="form-label">Nome</label>
                                <input type="text" class="form-control border border-2" id="cardName" name="cardName">
                            </div>
                            <div class="mb-3">
                                <label for="cardSurname" class="form-label">Cognome</label>
                                <input type="text" class="form-control border border-2" id="cardSurname" name="cardSurname">
                            </div>
                            <div class="mb-3">
                                <label for="cardExpiration" class="form-label">Data di Scadenza</label>
                                <input type="month" class="form-control border border-2" id="cardExpiration" name="cardExpiration">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Conferma Carta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    document.querySelectorAll('input[name="credit_card"]').forEach((radio) => {
        radio.addEventListener('change', function() {
            var newCardForm = document.getElementById('new-card-form');
            if (this.value === 'new') {
                newCardForm.querySelectorAll('input').forEach((input) => {
                    input.required = true;
                });
                newCardForm.classList.remove("d-none");
            } else {
                newCardForm.querySelectorAll('input').forEach((input) => {
                    input.required = false;
                });
                newCardForm.classList.add("d-none");
            }
        });
    });
</script>

<?php
include '../includes/footer.php';
?>