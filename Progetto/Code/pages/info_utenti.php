<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn() || !isAdminLoggedIn()) {
    header("Location: login.php");
    exit();
}

try {
    $users = $dbh->getAllUsers();
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
    $users = [];
}

include("../includes/header.php");
?>

<main>
    <link rel="stylesheet" href="../CSS/styles.css">
    <div class="container py-5">
        <h1 class="mb-4">Gestione Utenti Registrati</h1>
        
        <?php if (!empty($users)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Email</th>
                            <th>Amministratore</th>
                            <th>Luogo</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['Email']) ?></td>
                                <td><?= $user['AdminClient'] ? 'Sì' : 'No' ?></td>
                                <td><?= htmlspecialchars($user['luogo'] ?? 'Non specificato') ?></td>
                                <td>
                                    <a href="user_details.php?id=<?= $user['Email'] ?>" class="btn btn-info btn-sm">Dettagli</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Nessun utente registrato nel sistema</div>
        <?php endif; ?>
    </div>
</main>

<?php
include("../includes/footer.php");
?>