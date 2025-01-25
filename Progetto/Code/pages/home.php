<?php
include("../includes/header.php"); // Include il file header.php
include("../includes/sidebar.php");
// Ottieni le categorie dalla funzione
// Recupera l'ultimo prodotto
$lastProduct = $dbh->getLastProductID();

// Recupera il prodotto più venduto
$topProduct = $dbh->getTopSelledProduct();
?>
<main class="container-fluid py-4">
    
    <div class="container-page">
        <div class="row">
            <!-- Contenuto principale -->
            <section class="col-md-9 col-sm-12 mx-auto">
                <div id="main-content">
                    <!-- Contenuto della pagina -->
                    <h1 class="text-center">Benvenuto su CraftMania!</h1>
                    <p class="text-center">Esplora la nostra collezione di prodotti artigianali.</p>

                    <!-- Slider -->
                    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/cucito.jpg" class="d-block w-75 mx-auto" alt="Top Product">
                            </div>
                            <div class="carousel-item">
                                <img src="images/maschera.jpg" class="d-block w-75 mx-auto" alt="Top Product">
                            </div>
                            <div class="carousel-item">
                                <img src="images/vaso.jpg" class="d-block w-75 mx-auto" alt="Top Product">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <!-- Sezione Prodotti -->

                    <div class="row mt-4 text-center justify-content-center">
                        <!-- Ultimi prodotti -->
                        <div class="col-md-5 offset-md-1">
                            <div class="card">
                                <?php if (!empty($lastProduct) && isset($lastProduct[0]['Nome'], $lastProduct[0]['PathImmagine'], $lastProduct[0]['Costo'])): ?>
                                <a href="product.php?productId=<?= urlencode($lastProduct[0]['productID']) ?>">
                                    <img src="<?= htmlspecialchars($lastProduct[0]['PathImmagine']) ?>"
                                        class="card-img-top mx-auto"
                                        alt="<?= htmlspecialchars($lastProduct[0]['Nome']) ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="product.php?productId=<?= urlencode($lastProduct[0]['productID']) ?>"
                                            class="text-dark">
                                            <?= htmlspecialchars($lastProduct[0]['Nome']) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text"><strong>€ <?= number_format($lastProduct[0]['Costo'], 2) ?></strong></p>
                                </div>
                                <?php else: ?>
                                <div class="card-body">
                                    <p class="card-text">Nessun prodotto trovato.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Top prodotti del mese -->
                        <div class="col-md-5">
                            <div class="card">
                                <?php if (!empty($topProduct) && isset($topProduct[0]['Nome'], $topProduct[0]['PathImmagine'], $topProduct[0]['Costo'], $topProduct[0]['vendite'])): ?>
                                <a href="product.php?productId=<?= urlencode($topProduct[0]['productID']) ?>">
                                    <img src="<?= htmlspecialchars($topProduct[0]['PathImmagine']) ?>"
                                        class="card-img-top mx-auto"
                                        alt="<?= htmlspecialchars($topProduct[0]['Nome']) ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="product.php?productId=<?= urlencode($topProduct[0]['productID']) ?>"
                                            class="text-dark">
                                            <?= htmlspecialchars($topProduct[0]['Nome']) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text">Vendite: <?= htmlspecialchars($topProduct[0]['vendite']) ?></p>
                                    <p class="card-text"><strong>€ <?= number_format($topProduct[0]['Costo'], 2) ?></strong></p>
                                </div>
                                <?php else: ?>
                                <div class="card-body">
                                    <p class="card-text">Nessun prodotto trovato.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                </div>
            </section>
        </div>
    </div>
</main>

<?php
include("../includes/footer.php"); // Include il file footer.php
?>
