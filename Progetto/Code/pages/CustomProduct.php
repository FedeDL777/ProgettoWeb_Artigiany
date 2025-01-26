<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

$materials = $dbh->getMaterials();
include("../includes/header.php");
?>

<main>
    <div class="container-page">
        <div id="main-content">
            <div class="container my-4">
                <h1 class="text-center">Crea il tuo prodotto personalizzato!</h1>
                <p class="text-center">Seleziona il materiale e il colore del tuo pezzo personalizzato, dagli una forma
                    e dacci una descrizione per darci una linea guida da seguire</p>

                <div class="row">
                    <!-- Sezione controlli -->
                    <div class="row">
                        <!-- Sezione Colore -->
                        <div class="col-lg-6 col-md-12">
                            <div class="controls-section">
                                <div class="mb-4">
                                    <h5>Seleziona Colore</h5>
                                    <div class="color-palette" id="colorPalette">
                                        <div class="color-option" style="background-color: #007bff"
                                            data-color="#007bff"></div>
                                        <div class="color-option" style="background-color: #dc3545"
                                            data-color="#dc3545"></div>
                                        <div class="color-option" style="background-color: #28a745"
                                            data-color="#28a745"></div>
                                        <div class="color-option" style="background-color: #ffc107"
                                            data-color="#ffc107"></div>
                                        <div class="color-option" style="background-color: #6f42c1"
                                            data-color="#6f42c1"></div>
                                        <div class="color-option" style="background-color: #fd7e14"
                                            data-color="#fd7e14"></div>
                                        <div class="color-option" style="background-color: #e83e8c"
                                            data-color="#e83e8c"></div>
                                        <div class="color-option" style="background-color: #17a2b8"
                                            data-color="#17a2b8"></div>
                                        <div class="color-option eraser" data-color="#ffffff">🧽</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sezione Materiale -->
                        <div class="col-lg-6 col-md-12">
                            <div class="mb-4">
                                <h5>Seleziona Materiale</h5>
                                 <label for="materialSelect">Materiale:</label>
                                <select id="materialSelect" class="form-select">
                                    <option value="" selected disabled hidden>Nome materiale - prezzo per cm quadrato</option>
                                    <?php foreach ($materials as $material): ?>
                                    <option value="<?= htmlspecialchars($material['Nome']) ?>">
                                        <?= htmlspecialchars($material['Nome']) ?> - €
                                        <?= htmlspecialchars(number_format($material['CostoXquadretto'], 2, ',', '.')) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Griglia -->
                        <div class="col-lg-9 col-md-12 mx-auto mb-4 text-center">
                            <div>
                                <h5>Crea la tua forma</h5>
                                <div id="grid" class="grid-container"></div>
                            </div>
                        </div>

                        <!-- Descrizione e bottone -->
                        <div class="col-lg-8 col-md-12 mx-auto mb-4 text-center">
                            <div class="mb-4">
                                <h5>Descrizione</h5>
                                <label for="description">Descrivi il tuo prodotto:</label>
                                <textarea id="description" class="form-control" rows="4"
                                    placeholder="Descrivi il tuo prodotto (obbligatorio)"></textarea>
                            </div>

                            <button id="submitBtn" class="btn btn-primary w-100" disabled>Invia</button>
                        </div>
                    </div>
                </div>

                <form id="customProductForm" method="POST" action="add_custom_product.php">
                    <input type="hidden" name="material" id="formMaterial">
                    <input type="hidden" name="description" id="formDescription">
                    <input type="hidden" name="selectedCells" id="formSelectedCells">
                    <input type="hidden" name="totalPrice" id="formTotalPrice">
                </form>
            </div>
        </div>
    </div>
</main>

<script src="../utils/custom_product.js"></script>

<?php include("../includes/footer.php"); ?>