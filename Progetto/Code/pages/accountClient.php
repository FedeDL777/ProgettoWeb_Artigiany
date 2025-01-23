<?php
include("../includes/header.php"); // Include il file header.php
?>
<main class="container-fluid py-4">
    <link rel="stylesheet" href="../CSS/styles.css">
    <div class="container-page">
        <div class="row">
            <!-- Contenuto principale -->
            <section class="col-md-12">
                <div id="main-content">
                    <!-- Contenuto della pagina del profilo -->
                    <h1 class="text-center">Il mio account:</h1>

                    <div class="row mt-4 text-center">
                        <div class="col-md-6 col-sm-12 mb-4">
                            <a href="info_carte.php" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-start">
                                        <i class="bi bi-credit-card-2-front-fill me-3" style="font-size: 2rem;"></i>
                                        <h5 class="card-title mb-0" style="font-size: 1.5rem;">Info Carte</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-4">
                            <a href="miei_ordini.php" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-start">
                                        <i class="bi bi-cart-check-fill me-3" style="font-size: 2rem;"></i>
                                        <h5 class="card-title mb-0" style="font-size: 1.5rem;">I miei ordini</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-4">
                            <a href="miei_pagamenti.php" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-start">
                                        <i class="bi bi-currency-exchange me-3" style="font-size: 2rem;"></i>
                                        <h5 class="card-title mb-0" style="font-size: 1.5rem;">I miei pagamenti</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-4">
                            <a href="impostazioni_consegna.php" class="text-decoration-none">
                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-start">
                                        <i class="bi bi-truck me-3" style="font-size: 2rem;"></i>
                                        <h5 class="card-title mb-0" style="font-size: 1.5rem;">Impostazioni di consegna</h5>
                                    </div>
                                </div>
                            </a>
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
