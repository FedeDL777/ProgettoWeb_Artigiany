<?php
session_start();
include_once("../includes/bootstrap.php");
include_once("../includes/functions.php");

// Verifica se l'utente è loggato
if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Recupera l'email dell'utente loggato
$email = getLoggedUserEmail();

// Verifica se esiste un luogo associato all'email
$userAddress = $dbh->getUserAddress($email);

if (!$userAddress) {
    // Se non esiste un luogo di consegna, reindirizza alla pagina delle impostazioni
    header("Location: impostazioni_consegna.php");
    exit();
} else {
    // Se esiste un luogo di consegna, reindirizza alla pagina di pagamento
    header("Location: miei_pagamenti.php");
    exit();
}