<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$email = getloggeduseremail();


try {
    $cards = $dbh->getUserCards($email);
} catch (Exception $e) {
    error_log("Errore: " . $e->getMessage());
    $cards = [];
}

include("../includes/header.php");
?>

<main>
    <div class="container-page">
        <div class="row">
    <div class="container py-5">
        <h1 class="mb-4">Le tue carte di pagamento</h1>
        
        <?php if (empty($cards)): ?>
            <div class="alert alert-info">Nessuna carta registrata</div>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Intestatario</th>
                        <th>Numero Carta</th>
                        <th>Scadenza</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cards as $card): ?>
                        <tr>
                            <td><?= htmlspecialchars($card['Nome'] . ' ' . $card['Cognome']) ?></td>
                            <td><?= maskCardNumber($card['Numero']) ?></td>
                            <td><?= htmlspecialchars(date('m/Y', strtotime($card['Scadenza']))) ?></td>
                            <td>
                                <form method="post" action="delete_card.php" style="display:inline;">
                                    <input type="hidden" name="numero" value="<?= $card['Numero'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="mt-4">
            <a href="add_card.php" class="btn btn-primary">Aggiungi una carta</a>
        </div>
    </div>
</div>
    </div>
</main>

<?php
include("../includes/footer.php");

// Funzione per mascherare il numero della carta

?>