<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

include("../includes/header.php");

$productID = isset($_GET['productID']) ? intval($_GET['productID']) : 0;

if ($productID <= 0) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Prodotto non valido!</div></div>";
    include("../includes/footer.php");
    exit();
}

// Recupera i dettagli del prodotto
$product = $dbh->getProductById($productID);

if (!$product) {
    echo "<div class='container py-5'><div class='alert alert-danger'>Prodotto non trovato!</div></div>";
    include("../includes/footer.php");
    exit();
}

// Recupera la griglia del prodotto personalizzato
$grid = $dbh->getCustomProductGrid($productID);

?>

<main>
    <div class="container-page">
        <div class="row">
            <div class="container py-5">
                <h1 class="mb-4 text-center">Griglia del Prodotto</h1>

                <?php if (empty($grid)): ?>
                    <div class="alert alert-info text-center">Nessuna griglia disponibile per questo prodotto!</div>
                <?php else: ?>
                    <div class="grid-container" style="display: grid; grid-template-columns: repeat(20, 20px); gap: 1px;">
                        <?php
                        // Creiamo un array vuoto per rappresentare la griglia
                        $gridSize = 20;
                        $emptyGrid = array_fill(0, $gridSize, array_fill(0, $gridSize, '#ffffff'));

                        // Popoliamo la griglia con i colori delle celle salvate
                        foreach ($grid as $cell) {
                            $row = intval($cell['row']);
                            $col = intval($cell['col']);
                            $color = $cell['color'] ?? '#ffffff'; // Default bianco se il colore non è specificato
                            $emptyGrid[$row][$col] = $color;
                        }

                        // Generiamo la griglia HTML
                        for ($row = 0; $row < $gridSize; $row++) {
                            for ($col = 0; $col < $gridSize; $col++) {
                                $cellColor = $emptyGrid[$row][$col];
                                echo "<div style='width: 20px; height: 20px; background-color: {$cellColor}; border: 1px solid #ccc;'></div>";
                            }
                        }
                        ?>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="home.php" class="btn btn-outline-secondary">Torna alla Home</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include("../includes/footer.php");
?>