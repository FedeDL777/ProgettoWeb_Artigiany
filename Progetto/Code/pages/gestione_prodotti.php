<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn() || !isAdminLoggedIn()) {
    header("Location: ../login.php");
    exit();
}

$error = '';
$success = '';
$uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/ProgettoWeb_Artigiany/Progetto/Code/uploads/';

// Gestione form aggiunta prodotto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanificazione input
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $descrizione = filter_input(INPUT_POST, 'descrizione', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $costo = filter_input(INPUT_POST, 'costo', FILTER_VALIDATE_FLOAT, ["options" => ["min_range" => 0.01]]);
        $categoryID = filter_input(INPUT_POST, 'categoria', FILTER_VALIDATE_INT);
        $materiali = $_POST['materiali'] ?? [];

        // Validazione
        if (empty($nome) || strlen($nome) > 25) {
            throw new Exception("Nome prodotto non valido (max 25 caratteri)");
        }

        if (empty($descrizione) || strlen($descrizione) > 1000) {
            throw new Exception("Descrizione non valida (max 1000 caratteri)");
        }

        if ($costo === false || $costo <= 0) {
            throw new Exception("Prezzo non valido");
        }

        if ($categoryID === false || $categoryID <= 0) {
            throw new Exception("Seleziona una categoria valida");
        }

        // Controllo file upload
        if (empty($_FILES['immagine']['name'])) {
            throw new Exception("Seleziona un'immagine");
        }

        // Upload immagine
        list($result, $message) = uploadImage($uploadPath, $_FILES['immagine']);
        if ($result !== 1) {
            throw new Exception($message);
        }

        // Percorso relativo per il database
        $immaginePath = '/ProgettoWeb_Artigiany/Progetto/Code/uploads/' . $message;

        // Inserimento transazionale
        $dbh->beginTransaction();

        try {
            // Inserimento prodotto
            $productId = $dbh->insertProduct(
                $nome,
                $descrizione,
                $costo,
                $immaginePath,
                $categoryID,
                0
            );

            if (!$productId) {
                throw new Exception("Errore nell'inserimento del prodotto");
            }

            // Inserimento materiali
            foreach ($materiali as $materiale) {
                if (!$dbh->insertProductMaterial($productId, $materiale)) {
                    throw new Exception("Errore nell'assegnazione dei materiali");
                }
            }

            $dbh->commit();
            $success = "Prodotto aggiunto con successo! ID: $productId";

        } catch (Exception $e) {
            $dbh->rollback();
            // Elimina immagine caricata in caso di errore
            if (file_exists($uploadPath . $message)) {
                unlink($uploadPath . $message);
            }
            throw $e;
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Recupera dati per il form
$categorie = $dbh->getCategories();
$materialiDisponibili = $dbh->getMaterials();
$prodotti = $dbh->getAllProducts();

include("../includes/header.php");
?>

<main class="container-fluid py-4">
    <div class="container-page">
        <h1 class="mb-4">Gestione Prodotti</h1>

        <!-- Messaggi di stato -->
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Form Aggiunta Prodotto -->
        <div class="card mb-5 shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="card-title mb-0">Aggiungi Nuovo Prodotto</h2>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <!-- Nome Prodotto -->
                        <div class="col-md-6">
                            <label class="form-label">Nome Prodotto <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control" 
                                   maxlength="25" required
                                   placeholder="Inserisci nome prodotto">
                        </div>

                        <!-- Prezzo -->
                        <div class="col-md-6">
                            <label class="form-label">Prezzo (€) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" name="costo" step="0.01" 
                                       class="form-control" min="0.01" 
                                       required placeholder="0.00">
                            </div>
                        </div>

                        <!-- Descrizione -->
                        <div class="col-12">
                            <label class="form-label">Descrizione <span class="text-danger">*</span></label>
                            <textarea name="descrizione" class="form-control" 
                                      rows="3" maxlength="1000" 
                                      required placeholder="Inserisci descrizione prodotto"></textarea>
                        </div>

                        <!-- Categoria -->
                        <div class="col-md-6">
                            <label class="form-label">Categoria <span class="text-danger">*</span></label>
                            <select name="categoria" class="form-select" required>
                                <option value="">Seleziona categoria</option>
                                <?php foreach ($categorie as $categoria): ?>
                                    <option value="<?= $categoria['categoryID'] ?>">
                                        <?= htmlspecialchars($categoria['Nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Materiali -->
                        <div class="col-md-6">
                            <label class="form-label">Materiali</label>
                            <div class="materiali-container border p-3 rounded">
                                <?php foreach ($materialiDisponibili as $materiale): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" 
                                               name="materiali[]" 
                                               value="<?= htmlspecialchars($materiale['Nome']) ?>"
                                               id="material-<?= htmlspecialchars($materiale['Nome']) ?>">
                                        <label class="form-check-label" for="material-<?= htmlspecialchars($materiale['Nome']) ?>">
                                            <?= htmlspecialchars($materiale['Nome']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Upload Immagine -->
                        <div class="col-12">
                            <label class="form-label">Immagine Prodotto <span class="text-danger">*</span></label>
                            <input type="file" name="immagine" 
                                   class="form-control" 
                                   accept="image/jpeg, image/png, image/gif" 
                                   required>
                            <small class="form-text text-muted">
                                Formati accettati: JPG, PNG, GIF (Max 500KB)
                            </small>
                        </div>

                        <!-- Pulsante Submit -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-2"></i>Aggiungi Prodotto
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista Prodotti Esistenti -->
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h2 class="card-title mb-0">Prodotti nel Catalogo</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Prezzo</th>
                                <th>Categoria</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($prodotti as $prodotto): 
                                $categoria = $dbh->getCategoryById($prodotto['categoryID']);
                            ?>
                                <tr>
                                    <td><?= $prodotto['productID'] ?></td>
                                    <td><?= htmlspecialchars($prodotto['Nome']) ?></td>
                                    <td>€ <?= number_format($prodotto['Costo'], 2) ?></td>
                                    <td><?= htmlspecialchars($categoria['Nome'] ?? 'N/A') ?></td>
                                    <td>
                                        <a href="modifica_prodotto.php?id=<?= $prodotto['productID'] ?>" 
                                           class="btn btn-sm btn-warning" 
                                           title="Modifica">
                                           <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="elimina_prodotto.php?id=<?= $prodotto['productID'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           title="Elimina"
                                           onclick="return confirm('Sei sicuro di voler eliminare questo prodotto?')">
                                           <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include("../includes/footer.php"); ?>