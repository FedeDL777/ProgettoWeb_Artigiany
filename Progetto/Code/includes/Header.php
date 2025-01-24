<!DOCTYPE html>
<html lang="it">
<!-- Header del sito per tutte le pages -->
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link href="/assets/css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="description" content="L'artigianalità italiana a casa tua!" />
    <meta name="author" content="Pietro Sbaraccani, Baloons, Alex Guerrini" />
    <title>Artigiany</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<?php
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");
$hasUnreadNotifications = false;
if (isUserLoggedIn()) {
    //TO DO
    //$hasUnreadNotifications = $db->hasUnreadNotifications($_SESSION["email"]);
}
?>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php"><strong>Artigiany</strong></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <form class="d-flex align-items-center my-2 my-lg-0 ms-auto" action="search.php" method="GET">
                    <label for="searched-product" class="visually-hidden">Cerca</label>
                    <input class="form-control me-2" type="search" name="searched-product" id="searched-product" placeholder="Cerca" value="<?= isset($_GET['searched-product']) ? $_GET['searched-product'] : "" ?>"/>
                    <button class="btn btn-outline-success" type="submit">Cerca</button>
                </form>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="customproduct.php">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Prodotto Personalizzato
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="bi bi-cart-fill"></i> Carrello
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">
                            <i class="bi bi-person-fill"></i> Profilo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Log-out
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notifications.php">
                            <i class="bi bi-bell-fill <?php if ($hasUnreadNotifications) echo 'redNotify' ?>"></i> Notifiche
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>