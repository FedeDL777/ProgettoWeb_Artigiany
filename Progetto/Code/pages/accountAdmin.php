<?php
include("../includes/header.php"); // Include il file header.php
?>
<main class="container-fluid py-4">
    <div class="container-page">
        <div class="row">
            <!-- Contenuto principale -->
            <section class="col-md-8 col-sm-12 mx-auto">
                <div id="main-content">
                    <!-- Contenuto della pagina admin -->
                    <h1 class="text-center">Account Admin:</h1>

                    <div class="row mt-4 text-center">
                        <div class="col-md-6 col-sm-12 mb-4">
                            <a href="info_utenti.php" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-start">
                                        <i class="bi bi-info-circle-fill me-3" style="font-size: 2rem;"></i>
                                        <h5 class="card-title mb-0" style="font-size: 1.5rem;">Info Utenti</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-4">
                            <a href="gestione_prodotti.php" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-start">
                                        <i class="bi bi-bag-fill me-3" style="font-size: 2rem;"></i>
                                        <h5 class="card-title mb-0" style="font-size: 1.5rem;">Gestione Prodotti</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!--
                        <div class="col-md-6 col-sm-12 mb-4">
                            <a href="info_pagamenti.php" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-start">
                                        <i class="bi bi-credit-card-2-back-fill me-3" style="font-size: 2rem;"></i>
                                        <h5 class="card-title mb-0" style="font-size: 1.5rem;">Info Pagamenti</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-4">
                            <a href="info_consegne.php" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-start">
                                        <i class="bi bi-map-fill me-3" style="font-size: 2rem;"></i>
                                        <h5 class="card-title mb-0" style="font-size: 1.5rem;">Info Consegne</h5>
                                    </div>
                                </div>
                            </a>
                        </div>-->
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>
<?php
include("../includes/footer.php"); // Include il file footer.php
?>
