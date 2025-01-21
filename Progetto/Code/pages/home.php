<?php
include("../includes/header.php"); // Include il file header.php
?>
<main class="container-fluid py-4">
<link rel="stylesheet" href="../CSS/styles.css">
    <!-- Contenuto della pagina -->
    <h1 class="text-center">Benvenuto su CraftMania!</h1>
    <p class="text-center">Esplora la nostra collezione di prodotti artigianali.</p>

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/cucito.jpg" class="d-block w-75" alt="Top Product">
            </div>
            <div class="carousel-item">
                <img src="images/maschera.jpg" class="d-block w-75" alt="Top Product">
            </div>
            <div class="carousel-item">
                <img src="images/vaso.jpg" class="d-block w-75" alt="Top Product">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="row mt-4 text-center">
        <div class="col-md-6">
            <div class="card">
                <img src="https://via.placeholder.com/400x200" class="card-img-top mx-auto" alt="Ultimi Prodotti">
                <div class="card-body">
                    <p class="card-text">Ultimi prodotti visualizzati</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <img src="https://via.placeholder.com/400x200" class="card-img-top mx-auto" alt="Top Prodotti">
                <div class="card-body">
                    <p class="card-text">Top prodotti del mese</p>
                </div>
            </div>
        </div>
    </div>

</main>
<?php
include("../includes/footer.php"); // Include il file footer.php
?>