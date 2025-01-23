<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = getLoggedUserEmail();
    
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $numero = str_replace(' ', '', $_POST['numero']);
    $month = $_POST['scadenza'];
    $scadenza = date('Y-m-t', strtotime($month)); // Ultimo giorno del mese

    // Validazione avanzata
    $errors = [];
    
    if (!preg_match('/^[a-zA-Z ]+$/', $nome)) {
        $errors[] = "Nome non valido";
    }
    
    if (!preg_match('/^[a-zA-Z ]+$/', $cognome)) {
        $errors[] = "Cognome non valido";
    }
    
    if (!preg_match('/^\d{16}$/', $numero)) {
        $errors[] = "Numero carta non valido (16 cifre richieste)";
    }
    
    if (!preg_match('/^\d{4}-\d{2}$/', $_POST['scadenza'])) {
        $errors[] = "Formato scadenza non valido";
    }

    if (empty($errors)) {
        try {
            $result = $dbh->insertCreditCard(
                $email,
                $nome,
                $cognome,
                $numero,
                $scadenza
            );

            if ($result) {
                header("Location: info_carte.php?success=1");
                exit();
            } else {
                $error = "Errore durante l'inserimento";
            }
        } catch (Exception $e) {
            error_log("DB Error: " . $e->getMessage());
            $error = "Errore tecnico. Riprova più tardi.";
        }
    } else {
        $error = implode("<br>", $errors);
    }
}

include("../includes/header.php");
?>

<div class="container py-5">
    <h1>Aggiungi carta</h1>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nome intestatario</label>
                    <input type="text" name="nome" class="form-control" 
                           pattern="[A-Za-z ]+" required
                           value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Cognome intestatario</label>
                    <input type="text" name="cognome" class="form-control"
                           pattern="[A-Za-z ]+" required
                           value="<?= htmlspecialchars($_POST['cognome'] ?? '') ?>">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Numero carta</label>
                    <input type="text" name="numero" class="form-control"
                           placeholder="1234567890123456" 
                           pattern="\d{16}" required
                           value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Scadenza</label>
                    <input type="month" name="scadenza" class="form-control" 
                           min="<?= date('Y-m') ?>" required
                           value="<?= htmlspecialchars($_POST['scadenza'] ?? '') ?>">
                </div>
            </div>
        </div>
        
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-credit-card me-2"></i>Salva carta
            </button>
        </div>
    </form>
</div>

<?php include("../includes/footer.php"); ?>