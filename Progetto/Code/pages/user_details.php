<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Controllo se l'utente è loggato e se è un amministratore
if (!isUserLoggedIn() || !isAdminLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Controllo se l'email è presente nella query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: manage_users.php"); // Redirect alla pagina di gestione utenti
    exit();
}

$email = htmlspecialchars($_GET['id']);

try {
    // Recupera i dettagli dell'utente dal database
    $userDetails = $dbh->getUser($email);
    $userDetails = $userDetails[0];
    if (!$userDetails) {
        throw new Exception("Utente non trovato.");
    }
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
    $userDetails = null;
}
$card = $dbh->getUserCards($email);
if (!$card) {
    $nome = "Nome non disponibile";
    $cognome = "Cognome non disponibile";
}
else {
    $card = $card[0];
    $nome = $card['Nome'];
    $cognome = $card['Cognome'];
}

include("../includes/header.php");
?>

<main>
    <div class="container py-5">
        <h1 class="mb-4">Dettagli Utente</h1>

        <?php if ($userDetails): ?>
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informazioni di <?= htmlspecialchars($nome . ' ' . $cognome) ?></h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Email</th>
                                <td><?= htmlspecialchars($userDetails['Email']) ?></td>
                            </tr>
                            <tr>
                                <th>Nome</th>
                                <td><?= htmlspecialchars($nome) ?></td>
                            </tr>
                            <tr>
                                <th>Cognome</th>
                                <td><?= htmlspecialchars($cognome) ?></td>
                            </tr>
                            <tr>
                                <th>Amministratore</th>
                                <td><?= $userDetails['AdminClient'] ? 'Sì' : 'No' ?></td>
                            </tr>
                            <tr>
                                <th>Luogo</th>
                                <td><?= htmlspecialchars($userDetails['luogo'] ?? 'Non specificato') ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="info_utenti.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Torna alla Gestione Utenti
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <p>Impossibile trovare i dettagli dell'utente.</p>
                <a href="manage_users.php" class="btn btn-secondary">Torna alla Gestione Utenti</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
include("../includes/footer.php");
?>