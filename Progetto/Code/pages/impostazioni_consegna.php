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

// Recupera l'indirizzo esistente, se disponibile
$user_address = $dbh->getUserAddress($email);

// Gestione del salvataggio dell'indirizzo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_address'])) {
   $address = htmlspecialchars(trim($_POST['luogo']));

    
    if (!empty($address)) {
        // Salva o aggiorna l'indirizzo dell'utente
        $result = $dbh->saveUserAddress($email, $address);
        if ($result) {
            echo "<div class='alert alert-success text-center'>Indirizzo salvato con successo!</div>";
        } else {
            echo "<div class='alert alert-danger text-center'>Errore durante il salvataggio dell'indirizzo. Riprova.</div>";
        }
    } else {
        echo "<div class='alert alert-warning text-center'>Per favore, inserisci un indirizzo valido.</div>";
    }
}

?>

<main>
    <div class="container-page">
        <div id="main-content">
    <link rel="stylesheet" href="../CSS/styles.css">
    <div class="container py-5">
        <h1 class="mb-4 text-center">Informazioni di Spedizione</h1>

        <form method="POST" action="" onsubmit="combineAddressFields()">
    <div class="mb-3">
        <label for="city" class="form-label">Città</label>
        <input 
            type="text" 
            id="city" 
            name="city" 
            class="form-control" 
            required>
    </div>
    <div class="mb-3">
        <label for="street" class="form-label">Via</label>
        <input 
            type="text" 
            id="street" 
            name="street" 
            class="form-control" 
            required>
    </div>
    <div class="mb-3">
        <label for="house_number" class="form-label">Numero Civico</label>
        <input 
            type="text" 
            id="house_number" 
            name="house_number" 
            class="form-control" 
            required>
    </div>
    <div class="mb-3">
        <label for="postal_code" class="form-label">Codice Postale</label>
        <input 
            type="text" 
            id="postal_code" 
            name="postal_code" 
            class="form-control" 
            required>
    </div>

    <!-- Campo nascosto per concatenare i valori -->
    <input type="hidden" id="combined_address" name="luogo">

    <div class="d-flex justify-content-between">
        <a href="cart.php" class="btn btn-outline-secondary">Torna al Carrello</a>
        <button type="submit" name="save_address" class="btn btn-success">Salva Indirizzo</button>
    </div>
</form>


    </div>
</div>
</div>
</main>
<script>
function combineAddressFields() {
    const city = document.getElementById("city").value.trim();
    const street = document.getElementById("street").value.trim();
    const houseNumber = document.getElementById("house_number").value.trim();
    const postalCode = document.getElementById("postal_code").value.trim();

    // Combina i valori separandoli con una virgola
    const combinedAddress = `${city}, ${street}, ${houseNumber}, ${postalCode}`;

    // Inserisci il valore nel campo nascosto
    document.getElementById("combined_address").value = combinedAddress;
}
</script>

<?php
include("../includes/footer.php");
?>
