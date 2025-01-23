<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = getLoggedUser();
    $data = [
        'Email' => $user['Email'],
        'Nome' => $_POST['nome'],
        'Cognome' => $_POST['cognome'],
        'Numero' => str_replace(' ', '', $_POST['numero']),
        'Scadenza' => $_POST['scadenza']
    ];

    try {
        $stmt = $dbh->prepare("INSERT INTO CARTA_DI_CREDITO (Email, Nome, Cognome, Numero, Scadenza) 
                             VALUES (:Email, :Nome, :Cognome, :Numero, :Scadenza)");
        $stmt->execute($data);
        header("Location: cards.php?success=1");
    } catch (PDOException $e) {
        error_log("Errore nell'inserimento della carta: " . $e->getMessage());
        header("Location: add_card.php?error=1");
    }
    exit();
}

include("../includes/header.php");
?>

<div class="container py-5">
    <h1>Aggiungi carta</h1>
    <form method="post">
        <div class="mb-3">
            <label>Nome intestatario</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Cognome intestatario</label>
            <input type="text" name="cognome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Numero carta</label>
            <input type="text" name="numero" class="form-control" placeholder="1234 5678 9012 3456" required>
        </div>
        <div class="mb-3">
            <label>Data scadenza</label>
            <input type="month" name="scadenza" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Salva carta</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>